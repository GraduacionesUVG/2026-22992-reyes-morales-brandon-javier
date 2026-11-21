<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable=['user_id','module_id','total_questions','correct_answers','score','started_at','completed_at'];

    protected function casts(): array{
        return [
            'total_questions'=>'integer',
            'correct_answers'=>'integer',
            'score'=>'decimal:2',
            'started_at'=>'datetime',
            'completed_at'=>'datetime',
        ];
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }

    public function module(): BelongsTo{
        return $this->belongsTo(Module::class);
    }

    public function answers(): HasMany{
        return $this->hasMany(QuizAttemptAnswer::class);
    }

    public function isCompleted(): bool{
        return !is_null($this->completed_at);
    }

    public function resultLabel(): string{
        return $this->score>=70?'Aprobado':'Necesita repaso';
    }
}