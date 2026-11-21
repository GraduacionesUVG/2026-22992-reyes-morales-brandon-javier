<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name','email','password','role'])]
#[Hidden(['password','remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory,Notifiable;

    public const ROLE_ADMIN='admin';
    public const ROLE_GESTOR='gestor';
    public const ROLE_ESTUDIANTE='estudiante';

    public function isAdmin(): bool{
        return $this->role===self::ROLE_ADMIN;
    }

    public function isGestor(): bool{
        return $this->role===self::ROLE_GESTOR;
    }

    public function isEstudiante(): bool{
        return $this->role===self::ROLE_ESTUDIANTE;
    }

    public function hasRole(string ...$roles): bool{
        return in_array($this->role,$roles,true);
    }

    public function lessonProgresses(): HasMany{
        return $this->hasMany(LessonProgress::class);
    }

    public function quizAttempts(): HasMany{
        return $this->hasMany(QuizAttempt::class);
    }

    public function lessonFavorites(): HasMany{
        return $this->hasMany(LessonFavorite::class);
    }

    protected function casts(): array{
        return [
            'email_verified_at'=>'datetime',
            'password'=>'hashed',
        ];
    }
}