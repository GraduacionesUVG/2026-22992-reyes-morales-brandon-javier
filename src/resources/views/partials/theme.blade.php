@php
    $settings=$siteSettings ?? \App\Models\SiteSetting::allSettings();

    $primary=$settings['primary_color'] ?? '#2563eb';
    $accent=$settings['accent_color'] ?? '#0f172a';
    $background=$settings['background_color'] ?? '#f8fafc';
    $text=$settings['text_color'] ?? '#0f172a';
    $card=$settings['card_color'] ?? '#ffffff';
    $stat=$settings['stat_color'] ?? '#2563eb';
    $sidebarActive=$settings['sidebar_active_color'] ?? '#eaf2ff';
    $sidebarActiveText=$settings['sidebar_active_text_color'] ?? '#2563eb';
    $success=$settings['success_color'] ?? '#16a34a';
    $font=$settings['font_family'] ?? 'Inter';
@endphp

<style>
:root{
    /* Variables del CMS */
    --cms-primary:{{ $primary }};
    --cms-accent:{{ $accent }};
    --cms-bg:{{ $background }};
    --cms-text:{{ $text }};
    --cms-card:{{ $card }};
    --cms-stat:{{ $stat }};
    --cms-sidebar-active:{{ $sidebarActive }};
    --cms-sidebar-active-text:{{ $sidebarActiveText }};
    --cms-success:{{ $success }};
    --cms-font:"{{ $font }}",-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;

    /* Compatibilidad con app.css */
    --color-primary:{{ $primary }};
    --color-primary-hover:color-mix(in srgb,{{ $primary }} 82%,black);
    --color-accent:{{ $accent }};
    --color-background:{{ $background }};
    --color-text:{{ $text }};
    --color-card:{{ $card }};
    --color-stat:{{ $stat }};
    --color-success:{{ $success }};

    /* Colores derivados */
    --color-muted:color-mix(in srgb,{{ $text }} 58%,{{ $background }});
    --color-muted-light:color-mix(in srgb,{{ $text }} 38%,{{ $background }});
    --color-border:color-mix(in srgb,{{ $text }} 13%,{{ $background }});
    --color-border-light:color-mix(in srgb,{{ $text }} 8%,{{ $background }});
    --color-soft:color-mix(in srgb,{{ $primary }} 8%,{{ $card }});
    --color-soft-hover:color-mix(in srgb,{{ $primary }} 12%,{{ $card }});
}

html,
body{
    font-family:var(--cms-font) !important;
    color:var(--cms-text);
}

body,
.app-body,
.app-shell,
.app-main,
.app-content,
.main-content,
.cms-page,
.dashboard-page,
.student-page,
.lesson-page{
    background:var(--cms-bg) !important;
    color:var(--cms-text);
}

/* =========================================================
   SIDEBAR
========================================================= */

.app-sidebar{
    background:var(--cms-card) !important;
    border-color:var(--color-border-light) !important;
}

.app-sidebar-header,
.app-sidebar-footer{
    border-color:var(--color-border-light) !important;
}

.app-brand{
    color:var(--cms-text) !important;
}

.app-brand-mark{
    background:var(--cms-primary) !important;
    color:#fff !important;
}

.app-brand-copy strong{
    color:var(--cms-text) !important;
}

.app-brand-copy small,
.app-nav-label,
.app-role-card small{
    color:var(--color-muted) !important;
}

.app-nav-link{
    color:color-mix(in srgb,var(--cms-text) 78%,var(--cms-bg)) !important;
}

.app-nav-link svg{
    color:currentColor;
    stroke:currentColor;
}

.app-nav-link:hover{
    background:var(--color-soft) !important;
    color:var(--cms-primary) !important;
}

.app-nav-link.is-active{
    background:var(--cms-sidebar-active) !important;
    color:var(--cms-sidebar-active-text) !important;
}

.app-nav-link.is-active span,
.app-nav-link.is-active svg{
    color:var(--cms-sidebar-active-text) !important;
    stroke:var(--cms-sidebar-active-text) !important;
}

.app-role-card{
    background:color-mix(in srgb,var(--cms-text) 5%,var(--cms-card)) !important;
}

.app-role-card strong{
    color:var(--cms-text) !important;
}

.app-role-dot{
    background:var(--cms-success) !important;
    box-shadow:0 0 0 3px color-mix(in srgb,var(--cms-success) 16%,transparent) !important;
}

/* =========================================================
   HEADER
========================================================= */

.app-header{
    background:color-mix(in srgb,var(--cms-card) 88%,transparent) !important;
    border-color:var(--color-border-light) !important;
}

.app-page-heading h1,
.app-user-button,
.app-user-info strong{
    color:var(--cms-text) !important;
}

.app-page-heading p,
.app-user-info small,
.app-user-chevron{
    color:var(--color-muted) !important;
}

.app-user-avatar{
    background:var(--cms-primary) !important;
    color:#fff !important;
}

.app-logout-button,
.app-mobile-menu,
.app-sidebar-close{
    color:var(--color-muted) !important;
}

.app-logout-button:hover,
.app-mobile-menu:hover,
.app-sidebar-close:hover,
.app-user-button:hover{
    background:var(--color-soft) !important;
    color:var(--cms-primary) !important;
}

/* =========================================================
   TÍTULOS Y ENLACES
========================================================= */

h1,
h2,
h3,
h4,
h5,
h6{
    color:var(--cms-text);
}

.cms-eyebrow,
.student-eyebrow,
.student-home-eyebrow,
.student-content-label,
.modules-heading>p,
.module-detail-heading>span,
.cms-back-link,
.authx-link,
.login-link,
.form-small-link,
.lesson-page-kicker span:first-child,
.lesson-external-resource,
.lesson-resource-open{
    color:var(--cms-primary) !important;
}

a{
    text-decoration-color:color-mix(in srgb,var(--cms-primary) 35%,transparent);
}

/* =========================================================
   BOTONES
========================================================= */

.cms-primary-button,
button.cms-primary-button,
a.cms-primary-button,
.student-primary-action,
.login-button{
    background:var(--cms-primary) !important;
    border-color:var(--cms-primary) !important;
    color:#fff !important;
}

.cms-primary-button:hover,
button.cms-primary-button:hover,
a.cms-primary-button:hover,
.student-primary-action:hover,
.login-button:hover{
    background:color-mix(in srgb,var(--cms-primary) 88%,black) !important;
}

.cms-secondary-button,
.cms-cancel-button,
.cms-secondary-button-accent{
    background:var(--cms-card) !important;
    color:var(--cms-primary) !important;
    border-color:var(--color-border) !important;
}

.cms-secondary-button:hover,
.cms-cancel-button:hover,
.cms-secondary-button-accent:hover{
    background:var(--color-soft) !important;
    color:var(--cms-primary) !important;
    border-color:var(--cms-primary) !important;
}

/* =========================================================
   ÍCONOS Y ELEMENTOS DESTACADOS
========================================================= */

.course-admin-icon,
.cms-module-number,
.module-number,
.student-progress-icon,
.student-tip-icon{
    background:var(--color-soft) !important;
    color:var(--cms-primary) !important;
    border-color:color-mix(in srgb,var(--cms-primary) 14%,transparent) !important;
}

.course-admin-icon svg,
.cms-module-number svg,
.module-number svg,
.student-progress-icon svg,
.student-tip-icon svg{
    color:var(--cms-primary) !important;
    stroke:var(--cms-primary) !important;
}

/* =========================================================
   TARJETAS
========================================================= */

.cms-form-card,
.cms-stat-card,
.dashboard-card,
.dashboard-stat-card,
.stat-card,
.student-progress-card,
.student-stat-card,
.student-section-card,
.lesson-content-card,
.lesson-material-card,
.lesson-resource-card,
.lesson-admin-resources,
.course-card,
.module-card,
.question-card,
.cms-module-course,
.authx-card,
.login-card{
    background:var(--cms-card) !important;
    border-color:var(--color-border-light) !important;
}

/* =========================================================
   TEXTO SECUNDARIO
========================================================= */

.student-welcome p:not(.student-eyebrow),
.student-card-label,
.student-progress-meta,
.cms-module-course-header p,
.cms-module-info p,
.cms-module-info small,
.lesson-page-heading>p,
.lesson-resource-heading span,
.lesson-materials-heading span,
.lesson-admin-resources-heading span,
.lesson-nav-link small,
.lesson-file-resource span,
.lesson-admin-resource small{
    color:var(--color-muted) !important;
}

/* =========================================================
   ESTADÍSTICAS Y PROGRESO
========================================================= */

.cms-stat-card strong,
.dashboard-stat-card strong,
.stat-card strong,
.student-home-stat strong{
    color:var(--cms-stat) !important;
}

.cms-stat-card .stat-bar,
.dashboard-stat-card .stat-bar,
.stat-card .stat-bar,
.cms-stat-card .progress-bar,
.dashboard-stat-card .progress-bar,
.stat-card .progress-bar,
.student-progress-track span{
    background:var(--cms-stat) !important;
}

.student-progress-track{
    background:color-mix(in srgb,var(--cms-text) 10%,var(--cms-card)) !important;
}

/* =========================================================
   FORMULARIOS
========================================================= */

input,
textarea,
select{
    background:var(--cms-card);
    color:var(--cms-text) !important;
    border-color:var(--color-border) !important;
}

input::placeholder,
textarea::placeholder{
    color:var(--color-muted-light) !important;
}

input:focus,
textarea:focus,
select:focus{
    border-color:var(--cms-primary) !important;
    box-shadow:0 0 0 4px color-mix(in srgb,var(--cms-primary) 14%,transparent) !important;
}

input[type="checkbox"],
input[type="radio"]{
    accent-color:var(--cms-primary);
}

/* =========================================================
   ÉXITO
========================================================= */

.cms-alert-success{
    background:color-mix(in srgb,var(--cms-success) 12%,var(--cms-card)) !important;
    border-color:color-mix(in srgb,var(--cms-success) 28%,var(--cms-card)) !important;
    color:color-mix(in srgb,var(--cms-success) 75%,black) !important;
}

.cms-alert-success span{
    color:var(--cms-success) !important;
}

/* =========================================================
   LECCIONES
========================================================= */

.lesson-content-text{
    color:var(--cms-text) !important;
}

.lesson-page-kicker span{
    background:color-mix(in srgb,var(--cms-text) 7%,var(--cms-card)) !important;
    color:var(--color-muted) !important;
}

.lesson-page-kicker span:first-child{
    background:var(--color-soft) !important;
    color:var(--cms-primary) !important;
}

.lesson-nav-link{
    color:var(--cms-text) !important;
}

.lesson-nav-link:hover strong{
    color:var(--cms-primary) !important;
}

.lesson-image-wrap,
.lesson-pdf-wrap{
    background:color-mix(in srgb,var(--cms-text) 4%,var(--cms-card)) !important;
}

/* =========================================================
   SELECCIÓN
========================================================= */

::selection{
    background:color-mix(in srgb,var(--cms-primary) 24%,var(--cms-card));
    color:var(--cms-text);
}
</style>