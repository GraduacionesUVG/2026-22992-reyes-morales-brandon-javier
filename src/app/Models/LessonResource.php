<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LessonResource extends Model
{
    protected $fillable=['lesson_id','title','resource_type','source_type','file_path','external_url','content','caption','mime_type','original_name','file_size','sort_order'];

    protected function casts(): array{
        return ['file_size'=>'integer','sort_order'=>'integer'];
    }

    public function lesson(): BelongsTo{
        return $this->belongsTo(Lesson::class);
    }

    public function url(): ?string{
        if($this->source_type==='upload'&&$this->file_path)return Storage::disk('public')->url($this->file_path);
        return $this->external_url;
    }

    public function embedUrl(): ?string{
        if($this->resource_type!=='video'||!$this->external_url)return null;
        $parts=parse_url($this->external_url);
        $host=strtolower($parts['host']??'');
        $path=trim($parts['path']??'','/');
        if(in_array($host,['youtube.com','www.youtube.com','m.youtube.com'],true)){
            parse_str($parts['query']??'',$query);
            if(isset($query['v']))return 'https://www.youtube.com/embed/'.rawurlencode($query['v']);
            if(str_starts_with($path,'shorts/')){
                $id=explode('/',$path)[1]??null;
                return $id?'https://www.youtube.com/embed/'.rawurlencode($id):null;
            }
            if(str_starts_with($path,'embed/')){
                $id=explode('/',$path)[1]??null;
                return $id?'https://www.youtube.com/embed/'.rawurlencode($id):null;
            }
        }
        if($host==='youtu.be'&&$path!=='')return 'https://www.youtube.com/embed/'.rawurlencode(explode('/',$path)[0]);
        if(in_array($host,['vimeo.com','www.vimeo.com'],true)&&preg_match('/^(\d+)/',$path,$matches))return 'https://player.vimeo.com/video/'.$matches[1];
        return null;
    }
}