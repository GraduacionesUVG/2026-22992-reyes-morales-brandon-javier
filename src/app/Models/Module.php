<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    protected $fillable=['course_id','title','slug','description','sort_order','status'];

    protected function casts(): array{
        return ['sort_order'=>'integer'];
    }

    public function course(): BelongsTo{
        return $this->belongsTo(Course::class);
    }

    public function lessons(): HasMany{
        return $this->hasMany(Lesson::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publishedLessons(): HasMany{
        return $this->hasMany(Lesson::class)->where('status','published')->orderBy('sort_order')->orderBy('id');
    }

    public function questions(): HasMany{
        return $this->hasMany(Question::class)->orderBy('sort_order')->orderBy('id');
    }

    public function publishedQuestions(): HasMany{
        return $this->hasMany(Question::class)->where('status','published')->orderBy('sort_order')->orderBy('id');
    }

    public function quizAttempts(): HasMany{
        return $this->hasMany(QuizAttempt::class);
    }

    public function scopePublished(Builder $query): Builder{
        return $query->where('status','published');
    }
}