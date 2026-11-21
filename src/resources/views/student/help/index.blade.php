@extends('layouts.app')
@section('title','Guía rápida · '.config('branding.site_name'))
@section('page_title','Guía rápida')
@section('page_description','Aprende cómo usar la plataforma')

@section('content')
<div class=cms-page">
    <header class="cms-page-header">
        <div>
            <p class="cms-ayebrow">Ayuda del estudiante</p>
            <h1>Guia rapido de uso</h1>
            <span>Conoce las funciones principales para estudiar, pracrtica y revisar tu avance.</span>
        </div>
        <a href="{{ route('student.modules.index') }}" class="cms-primary-button">Empezar a estudiar</a>
    </header>

    <section class="studen-home-stats">
        <div class="studen-home-stat"><span>Modulos disponibles</span><strong>{{ $availableModules }}</strong></div>
        <div class="studen-home-stat"><span>Quizzes disponibles</span><strong>{{ $availableQuizzes }}</strong></div>
        <div class="studen-home-stat"><span>Intentos de quiz completados</span><strong>{{ $quizAttempts }}</strong></div>
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header">
            <div>
                <h2>Funciones principales</h2>
                <p>Estos son los pasos recomendados para usar la plataforma.</p>
            </div>
        </div>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">1</div>
                <div class="course-admin-info">
                    <div class="course-admin-title-row"><h2>Revisar los módulos</h2><span class="cms-status is-published"><span></span>Contenido</span></div>
                    <p>En la sección de módulos puedes ver el contenido teórico publicado. Abre un módulo para consultar sus lecciones.</p>
                    <div class="course-admin-meta"><span>Recomendado para iniciar el aprendizaje.</span></div>
                </div>
            </div>
            <div class="course-admin-actions"><a href="{{ route('student.modules.index') }}" class="cms-secondary-button">Ir a módulos</a></div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">2</div>
                <div class="course-admin-info">
                    <div class="course-admin-title-row"><h2>Leer lecciones y marcar avance</h2><span class="cms-status is-published"><span></span>Progreso</span></div>
                    <p>Al terminar una lección puedes marcarla como completada. Esto actualiza tu porcentaje de progreso general.</p>
                    <div class="course-admin-meta"><span>Sirve para llevar control de estudio.</span></div>
                </div>
            </div>
            <div class="course-admin-actions"><a href="{{ route('student.progress.index') }}" class="cms-secondary-button">Ver progreso</a></div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">3</div>
                <div class="course-admin-info">
                    <div class="course-admin-title-row"><h2>Guardar favoritos</h2><span class="cms-status is-published"><span></span>Repaso</span></div>
                    <p>Usa favoritos para guardar lecciones importantes y repasarlas después sin tener que buscarlas nuevamente.</p>
                    <div class="course-admin-meta"><span>Útil para temas de normativa, señales y seguridad vial.</span></div>
                </div>
            </div>
            <div class="course-admin-actions"><a href="{{ route('student.favorites.index') }}" class="cms-secondary-button">Ver favoritos</a></div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">4</div>
                <div class="course-admin-info">
                    <div class="course-admin-title-row"><h2>Buscar contenido</h2><span class="cms-status is-published"><span></span>Búsqueda</span></div>
                    <p>El buscador permite encontrar cursos, módulos y lecciones publicadas usando palabras clave.</p>
                    <div class="course-admin-meta"><span>Ejemplos: licencia, señales, cinturón, tránsito.</span></div>
                </div>
            </div>
            <div class="course-admin-actions"><a href="{{ route('student.search.index') }}" class="cms-secondary-button">Buscar</a></div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">5</div>
                <div class="course-admin-info">
                    <div class="course-admin-title-row"><h2>Practicar con el simulador</h2><span class="cms-status is-published"><span></span>Evaluación</span></div>
                    <p>Cuando termines de estudiar, responde el simulador por módulo. Al finalizar verás tu resultado, respuestas correctas y retroalimentación.</p>
                    <div class="course-admin-meta"><span>Recomendado después de leer las lecciones.</span></div>
                </div>
            </div>
            <div class="course-admin-actions"><a href="{{ route('student.quiz.index') }}" class="cms-secondary-button">Ir al simulador</a></div>
        </article>
    </section>

    <section class="cms-list-card" style="margin-top:24px;">
        <div class="cms-form-card-header">
            <div>
                <h2>Recomendaciones de uso</h2>
                <p>Consejos para aprovechar mejor la plataforma.</p>
            </div>
        </div>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">✓</div>
                <div class="course-admin-info">
                    <h2>Estudia por orden</h2>
                    <p>Primero revisa los módulos y lecciones. Después utiliza el simulador para comprobar lo aprendido.</p>
                </div>
            </div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">✓</div>
                <div class="course-admin-info">
                    <h2>Usa favoritos para repasar</h2>
                    <p>Guarda las lecciones que consideres más importantes o difíciles.</p>
                </div>
            </div>
        </article>

        <article class="course-admin-item">
            <div class="course-admin-main">
                <div class="course-admin-icon">✓</div>
                <div class="course-admin-info">
                    <h2>Revisa tus resultados</h2>
                    <p>Después de cada simulador, analiza las respuestas incorrectas y vuelve a estudiar el contenido relacionado.</p>
                </div>
            </div>
        </article>
    </section>
</div>
@endsection