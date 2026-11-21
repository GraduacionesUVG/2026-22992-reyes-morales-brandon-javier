<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Content\CourseController;
use App\Http\Controllers\Content\LessonController as ContentLessonController;
use App\Http\Controllers\Content\ModuleController as ContentModuleController;
use App\Http\Controllers\Content\QuestionController as ContentQuestionController;
use App\Http\Controllers\Content\ReviewController as ContentReviewController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\FavoriteController as StudentFavoriteController;
use App\Http\Controllers\Student\HelpController as StudentHelpController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Controllers\Student\ModuleController as StudentModuleController;
use App\Http\Controllers\Student\ProgressController as StudentProgressController;
use App\Http\Controllers\Student\QuizController as StudentQuizController;
use App\Http\Controllers\Student\SearchController as StudentSearchController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.submit');

    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'show'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'show'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

Route::middleware(['auth', 'no.cache'])->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

    Route::get('/perfil', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/perfil', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/perfil/contrasena', [ProfileController::class, 'updatePassword'])
        ->name('profile.password.update');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        return match ($user->role) {
            User::ROLE_ADMIN =>
                redirect()->route('admin.dashboard'),

            User::ROLE_GESTOR =>
                redirect()->route('gestor.dashboard'),

            User::ROLE_ESTUDIANTE =>
                redirect()->route('student.dashboard'),

            default =>
                abort(403, 'El usuario no tiene un rol válido.'),
        };
    })->name('dashboard');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            Route::get('/dashboard', [AdminDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('usuarios', AdminUserController::class)
                ->except(['show'])
                ->parameters([
                    'usuarios' => 'user',
                ])
                ->names([
                    'index' => 'users.index',
                    'create' => 'users.create',
                    'store' => 'users.store',
                    'edit' => 'users.edit',
                    'update' => 'users.update',
                    'destroy' => 'users.destroy',
                ]);

            Route::resource('cursos', CourseController::class)
                ->except(['show'])
                ->parameters([
                    'cursos' => 'course',
                ])
                ->names([
                    'index' => 'courses.index',
                    'create' => 'courses.create',
                    'store' => 'courses.store',
                    'edit' => 'courses.edit',
                    'update' => 'courses.update',
                    'destroy' => 'courses.destroy',
                ]);

            Route::get('/modulos', [ContentModuleController::class, 'all'])
                ->name('modules.index');

            Route::get('/lecciones', [ContentLessonController::class, 'all'])
                ->name('lessons.index');

            Route::get('/preguntas', [ContentQuestionController::class, 'all'])
                ->name('questions.index');

            Route::get('/revision-contenido', [ContentReviewController::class, 'index'])
                ->name('review.index');

            Route::patch(
                '/revision-contenido/{type}/{id}/estado',
                [ContentReviewController::class, 'updateStatus']
            )->name('review.status');

            Route::get('/configuracion', [SiteSettingController::class, 'edit'])
                ->name('settings.edit');

            Route::put('/configuracion', [SiteSettingController::class, 'update'])
                ->name('settings.update');

            Route::prefix('cursos/{course}/modulos')
                ->name('courses.modules.')
                ->group(function () {

                    Route::get('/', [ContentModuleController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [ContentModuleController::class, 'create'])
                        ->name('create');

                    Route::post('/', [ContentModuleController::class, 'store'])
                        ->name('store');

                    Route::get('/{module}/edit', [ContentModuleController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{module}', [ContentModuleController::class, 'update'])
                        ->name('update');

                    Route::delete('/{module}', [ContentModuleController::class, 'destroy'])
                        ->name('destroy');

                    Route::prefix('{module}/lecciones')
                        ->name('lessons.')
                        ->group(function () {

                            Route::get('/', [ContentLessonController::class, 'index'])
                                ->name('index');

                            Route::get('/create', [ContentLessonController::class, 'create'])
                                ->name('create');

                            Route::post('/', [ContentLessonController::class, 'store'])
                                ->name('store');

                            Route::get('/{lesson}/edit', [ContentLessonController::class, 'edit'])
                                ->name('edit');

                            Route::put('/{lesson}', [ContentLessonController::class, 'update'])
                                ->name('update');

                            Route::delete(
                                '/{lesson}/recursos/{resource}',
                                [ContentLessonController::class, 'destroyResource']
                            )->name('resources.destroy');

                            Route::delete('/{lesson}', [ContentLessonController::class, 'destroy'])
                                ->name('destroy');
                        });

                    Route::prefix('{module}/preguntas')
                        ->name('questions.')
                        ->group(function () {

                            Route::get('/', [ContentQuestionController::class, 'index'])
                                ->name('index');

                            Route::get('/create', [ContentQuestionController::class, 'create'])
                                ->name('create');

                            Route::post('/', [ContentQuestionController::class, 'store'])
                                ->name('store');

                            Route::get('/{question}/edit', [ContentQuestionController::class, 'edit'])
                                ->name('edit');

                            Route::put('/{question}', [ContentQuestionController::class, 'update'])
                                ->name('update');

                            Route::delete('/{question}', [ContentQuestionController::class, 'destroy'])
                                ->name('destroy');
                        });
                });
        });

    Route::middleware('role:gestor')
        ->prefix('gestor')
        ->name('gestor.')
        ->group(function () {

            Route::get('/dashboard', [ManagerDashboardController::class, 'index'])
                ->name('dashboard');

            Route::resource('cursos', CourseController::class)
                ->except(['show'])
                ->parameters([
                    'cursos' => 'course',
                ])
                ->names([
                    'index' => 'courses.index',
                    'create' => 'courses.create',
                    'store' => 'courses.store',
                    'edit' => 'courses.edit',
                    'update' => 'courses.update',
                    'destroy' => 'courses.destroy',
                ]);

            Route::get('/modulos', [ContentModuleController::class, 'all'])
                ->name('modules.index');

            Route::get('/lecciones', [ContentLessonController::class, 'all'])
                ->name('lessons.index');

            Route::get('/preguntas', [ContentQuestionController::class, 'all'])
                ->name('questions.index');

            Route::get('/revision-contenido', [ContentReviewController::class, 'index'])
                ->name('review.index');

            Route::patch(
                '/revision-contenido/{type}/{id}/estado',
                [ContentReviewController::class, 'updateStatus']
            )->name('review.status');

            Route::prefix('cursos/{course}/modulos')
                ->name('courses.modules.')
                ->group(function () {

                    Route::get('/', [ContentModuleController::class, 'index'])
                        ->name('index');

                    Route::get('/create', [ContentModuleController::class, 'create'])
                        ->name('create');

                    Route::post('/', [ContentModuleController::class, 'store'])
                        ->name('store');

                    Route::get('/{module}/edit', [ContentModuleController::class, 'edit'])
                        ->name('edit');

                    Route::put('/{module}', [ContentModuleController::class, 'update'])
                        ->name('update');

                    Route::delete('/{module}', [ContentModuleController::class, 'destroy'])
                        ->name('destroy');

                    Route::prefix('{module}/lecciones')
                        ->name('lessons.')
                        ->group(function () {

                            Route::get('/', [ContentLessonController::class, 'index'])
                                ->name('index');

                            Route::get('/create', [ContentLessonController::class, 'create'])
                                ->name('create');

                            Route::post('/', [ContentLessonController::class, 'store'])
                                ->name('store');

                            Route::get('/{lesson}/edit', [ContentLessonController::class, 'edit'])
                                ->name('edit');

                            Route::put('/{lesson}', [ContentLessonController::class, 'update'])
                                ->name('update');

                            Route::delete(
                                '/{lesson}/recursos/{resource}',
                                [ContentLessonController::class, 'destroyResource']
                            )->name('resources.destroy');

                            Route::delete('/{lesson}', [ContentLessonController::class, 'destroy'])
                                ->name('destroy');
                        });

                    Route::prefix('{module}/preguntas')
                        ->name('questions.')
                        ->group(function () {

                            Route::get('/', [ContentQuestionController::class, 'index'])
                                ->name('index');

                            Route::get('/create', [ContentQuestionController::class, 'create'])
                                ->name('create');

                            Route::post('/', [ContentQuestionController::class, 'store'])
                                ->name('store');

                            Route::get('/{question}/edit', [ContentQuestionController::class, 'edit'])
                                ->name('edit');

                            Route::put('/{question}', [ContentQuestionController::class, 'update'])
                                ->name('update');

                            Route::delete('/{question}', [ContentQuestionController::class, 'destroy'])
                                ->name('destroy');
                        });
                });
        });

    Route::middleware('role:estudiante')
        ->prefix('estudiante')
        ->name('student.')
        ->group(function () {

            Route::get('/dashboard', [StudentDashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('/ayuda', [StudentHelpController::class, 'index'])
                ->name('help.index');

            Route::get('/modulos', [StudentModuleController::class, 'index'])
                ->name('modules.index');

            Route::get('/modulos/{module}', [StudentModuleController::class, 'show'])
                ->name('modules.show');

            Route::get('/lecciones/{lesson}', [StudentLessonController::class, 'show'])
                ->name('lessons.show');

            Route::post(
                '/lecciones/{lesson}/completar',
                [StudentLessonController::class, 'complete']
            )->name('lessons.complete');

            Route::delete(
                '/lecciones/{lesson}/completar',
                [StudentLessonController::class, 'uncomplete']
            )->name('lessons.uncomplete');

            Route::get('/progreso', [StudentProgressController::class, 'index'])
                ->name('progress.index');

            Route::get('/favoritos', [StudentFavoriteController::class, 'index'])
                ->name('favorites.index');

            Route::post(
                '/lecciones/{lesson}/favorito',
                [StudentFavoriteController::class, 'toggle']
            )->name('favorites.toggle');

            Route::delete(
                '/favoritos/{favorite}',
                [StudentFavoriteController::class, 'destroy']
            )->name('favorites.destroy');

            Route::get('/buscar', [StudentSearchController::class, 'index'])
                ->name('search.index');

            Route::get('/simulador', [StudentQuizController::class, 'index'])
                ->name('quiz.index');

            Route::get('/simulador/historial', [StudentQuizController::class, 'history'])
                ->name('quiz.history');

            Route::get(
                '/simulador/resultado/{attempt}',
                [StudentQuizController::class, 'result']
            )->name('quiz.result');

            Route::get(
                '/simulador/modulo/{module}',
                [StudentQuizController::class, 'show']
            )->name('quiz.show');

            Route::post(
                '/simulador/modulo/{module}',
                [StudentQuizController::class, 'submit']
            )->name('quiz.submit');
        });
});