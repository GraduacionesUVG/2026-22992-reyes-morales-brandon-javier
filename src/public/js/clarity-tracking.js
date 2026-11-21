
/* Microsoft Clarity - Plataforma educativa TG */
/* Analítica enviada directamente desde el navegador. */
(() => {
  'use strict';

  const ctx = window.tgClarityContext;
  if (!ctx || typeof window.clarity !== 'function') return;

  const prefix = 'tg_clarity_';

  const storage = {
    get(key) {
      try { return sessionStorage.getItem(prefix + key); }
      catch (_) { return null; }
    },
    set(key, value) {
      try { sessionStorage.setItem(prefix + key, value); }
      catch (_) {}
    },
    remove(key) {
      try { sessionStorage.removeItem(prefix + key); }
      catch (_) {}
    }
  };

  const emit = name => {
    if (/^[a-z][a-z0-9_]{0,63}$/.test(name || '')) {
      window.clarity('event', name);
    }
  };

  const tag = (key, value) => {
    if (value !== null && value !== undefined && value !== '') {
      window.clarity('set', key, String(value));
    }
  };

  const newSessionId = () => {
    if (window.crypto && typeof crypto.randomUUID === 'function') {
      return crypto.randomUUID();
    }
    return 'visit-' + Date.now() + '-' + Math.random().toString(36).slice(2);
  };

  let sessionId = storage.get('session');

  if (!sessionId) {
    sessionId = newSessionId();
    storage.set('session', sessionId);
  }

  // Contexto general de navegación.
  tag('environment', ctx.environment);
  tag('section', ctx.section);
  tag('route_name', ctx.route);
  tag('role', ctx.role);
  tag('auth_state', ctx.authenticated ? 'authenticated' : 'guest');

  // Contexto del recurso consultado.
  if (ctx.resources && typeof ctx.resources === 'object') {
    for (const [key, value] of Object.entries(ctx.resources)) {
      if (['course', 'module', 'lesson', 'question', 'attempt'].includes(key)) {
        tag(key + '_id', value);
      }
    }
  }

  // Identificar al usuario autenticado en cada página.
  if (ctx.authenticated && ctx.userId) {
    window.clarity(
      'identify',
      ctx.userId,
      sessionId,
      ctx.pageId,
      ctx.friendlyName || undefined
    );
  }

  // Resolver un intento previo de autenticación.
  const loginPending = storage.get('login_pending') === '1';

  if (loginPending && ctx.authenticated) {
    emit('login_success');
    storage.remove('login_pending');
  } else if (loginPending && ctx.loginFailed) {
    emit('login_failed');
    storage.remove('login_pending');
  } else if (
    loginPending &&
    !ctx.authenticated &&
    ctx.section === 'login'
  ) {
    // El navegador volvió al login sin autenticarse.
    emit('login_not_completed');
    storage.remove('login_pending');
  }

  // Resolver el cierre de sesión después de la redirección.
  const logoutPending = storage.get('logout_pending') === '1';

  if (logoutPending && !ctx.authenticated) {
    emit('logout_success');
    storage.remove('logout_pending');
    storage.remove('login_pending');
    storage.remove('session');
    sessionId = null;
  } else if (logoutPending && ctx.authenticated) {
    // El usuario todavía aparece autenticado.
    emit('logout_not_completed');
    storage.remove('logout_pending');
  }

  // Marcar la página visitada.
  emit('view_' + ctx.section);

  const routeEvents = {
    'student.quiz.index': 'quiz_index_viewed',
    'student.quiz.show': 'quiz_started_view',
    'student.quiz.result': 'quiz_result_viewed',
    'student.quiz.history': 'quiz_history_viewed',
    'student.favorites.index': 'favorites_list_viewed',
    'student.progress.index': 'progress_viewed',
    'student.lessons.show': 'lesson_opened',
    'student.modules.show': 'module_opened'
  };

  if (routeEvents[ctx.route]) {
    emit(routeEvents[ctx.route]);
  }

  if (
    ctx.section === 'busqueda' &&
    new URLSearchParams(location.search).has('q')
  ) {
    emit('search_results_viewed');
  }

  // Obtener ruta y método efectivo del formulario.
  const formInfo = form => {
    const url = new URL(
      form.getAttribute('action') || location.href,
      location.href
    );

    const spoof = form.querySelector('input[name="_method"]');
    const method = String(
      spoof ? spoof.value : form.method || 'GET'
    ).toUpperCase();

    return {
      path: url.pathname.replace(/\/+$/, '') || '/',
      method
    };
  };

  // Clasificar acciones según las rutas Laravel existentes.
  const formEvent = form => {
    const { path, method } = formInfo(form);

    if (path === '/login' && method === 'POST') return 'login_attempt';
    if (path === '/logout' && method === 'POST') return 'logout_requested';
    if (path === '/register' && method === 'POST') return 'registration_attempt';
    if (path === '/forgot-password' && method === 'POST') return 'password_reset_requested';

    if (path === '/perfil' && method === 'PATCH') {
      return 'profile_update_requested';
    }

    if (path === '/perfil/contrasena' && method === 'PUT') {
      return 'password_change_requested';
    }

    if (/^\/estudiante\/lecciones\/[^/]+\/completar$/.test(path)) {
      return method === 'DELETE'
        ? 'lesson_reopen_requested'
        : 'lesson_complete_requested';
    }

    if (
      /^\/estudiante\/lecciones\/[^/]+\/favorito$/.test(path) &&
      method === 'POST'
    ) {
      return 'favorite_toggle_requested';
    }

    if (
      /^\/estudiante\/favoritos\/[^/]+$/.test(path) &&
      method === 'DELETE'
    ) {
      return 'favorite_remove_requested';
    }

    if (
      /^\/estudiante\/simulador\/modulo\/[^/]+$/.test(path) &&
      method === 'POST'
    ) {
      return 'quiz_submit_requested';
    }

    if (path === '/estudiante/buscar' && method === 'GET') {
      return 'search_requested';
    }

    return null;
  };

  // Registrar formularios.
  document.addEventListener('submit', event => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) return;
    if (event.defaultPrevented) return;

    const { path, method } = formInfo(form);
    const name = form.dataset.clarityEvent || formEvent(form);

    if (name) emit(name);

    if (path === '/login' && method === 'POST') {
      storage.set('login_pending', '1');
      storage.remove('logout_pending');
    }

    if (path === '/logout' && method === 'POST') {
      storage.set('logout_pending', '1');
      storage.remove('login_pending');
    }
  });

  // Registrar enlaces y botones con eventos personalizados.
  document.addEventListener('click', event => {
    const target = event.target;

    if (!(target instanceof Element)) return;

    const el = target.closest('a,button,[data-clarity-event]');
    if (!el) return;

    // Los formularios ya son registrados por el evento submit.
    if (el.dataset.clarityEvent && !el.closest('form')) {
      emit(el.dataset.clarityEvent);
    }

    if (el.matches('a') && ctx.section === 'lecciones') {
      const href = el.getAttribute('href') || '';

      if (/\/estudiante\/lecciones\//.test(href)) {
        emit('lesson_navigation_clicked');
      }
    }
  });

  // Registrar interacción con opciones del simulador.
  // No se envían respuestas, preguntas ni valores seleccionados.
  document.addEventListener('change', event => {
    const input = event.target;

    if (!(input instanceof HTMLInputElement)) return;
    if (ctx.route !== 'student.quiz.show') return;

    if (input.type === 'radio' && input.name.startsWith('answers[')) {
      emit('quiz_answer_selected');
    }
  });

  // Registrar reproducción de videos HTML5.
  for (const type of ['play', 'pause', 'ended']) {
    document.addEventListener(type, event => {
      if (!(event.target instanceof HTMLVideoElement)) return;

      const names = {
        play: 'lesson_video_play',
        pause: 'lesson_video_pause',
        ended: 'lesson_video_ended'
      };

      emit(names[type]);
    }, true);
  }

  // Función disponible para futuras interacciones.
  window.trackClarityEvent = name => {
    emit(name);
  };
})();
