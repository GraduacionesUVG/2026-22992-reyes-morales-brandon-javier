<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable=['module_id','question_text','explanation','points','sort_order','status'];

    protected function casts(): array{
        return [
            'points'=>'integer',
            'sort_order'=>'integer',
        ];
    }

    public function module(): BelongsTo{
        return $this->belongsTo(Module::class);
    }

    public function options(): HasMany{
        return $this->hasMany(QuestionOption::class)->orderBy('sort_order')->orderBy('id');
    }

    public function correctOptions(): HasMany{
        return $this->hasMany(QuestionOption::class)->where('is_correct',true);
    }

    public function scopePublished(Builder $query): Builder{
        return $query->where('status','published');
    }

    public function scopeOrdered(Builder $query): Builder{
        return $query->orderBy('sort_order')->orderBy('id');
    }
}