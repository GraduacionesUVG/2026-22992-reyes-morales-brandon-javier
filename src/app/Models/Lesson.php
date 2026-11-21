<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable=['module_id','title','slug','summary','content','references','video_url','estimated_minutes','sort_order','status'];

    protected function casts(): array{
        return ['estimated_minutes'=>'integer','sort_order'=>'integer'];
    }

    public function module(): BelongsTo{
        return $this->belongsTo(Module::class);
    }

    public function resources(): HasMany{
        return $this->hasMany(LessonResource::class)->orderBy('sort_order')->orderBy('id');
    }

    public function favorites(): HasMany{
        return $this->hasMany(LessonFavorite::class);
    }

    public function scopePublished(Builder $query): Builder{
        return $query->where('status','published');
    }

    public function videoEmbedUrl(): ?string{
        if(!$this->video_url)return null;

        $parts=parse_url($this->video_url);
        $host=strtolower($parts['host']??'');
        $path=trim($parts['path']??'','/');

        if(in_array($host,['youtube.com','www.youtube.com','m.youtube.com'],true)){
            parse_str($parts['query']??'',$query);
            $id=$query['v']??null;
            return $id?'https://www.youtube.com/embed/'.rawurlencode($id):null;
        }

        if($host==='youtu.be'&&$path!==''){
            return 'https://www.youtube.com/embed/'.rawurlencode(explode('/',$path)[0]);
        }

        if(in_array($host,['vimeo.com','www.vimeo.com'],true)&&preg_match('/^(\d+)/',$path,$matches)){
            return 'https://player.vimeo.com/video/'.$matches[1];
        }

        return null;
    }
}