<?php

namespace App\Http\Controllers\Content;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonResource;
use App\Models\Module;
use App\Models\SiteSetting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function all(): View{
        $courses=Course::with([
            'modules'=>fn($q)=>$q->withCount('lessons')->orderBy('sort_order')->orderBy('id')
        ])->orderBy('sort_order')->orderBy('id')->get();

        return view('content.lessons.index',[
            'courses'=>$courses,
            'routePrefix'=>$this->routePrefix()
        ]);
    }

    public function index(Course $course,Module $module): View{
        $this->ensureModuleBelongsToCourse($course,$module);

        $module->load([
            'lessons'=>fn($q)=>$q->withCount('resources')->orderBy('sort_order')->orderBy('id')
        ]);

        return view('content.lessons.module',[
            'course'=>$course,
            'module'=>$module,
            'routePrefix'=>$this->routePrefix()
        ]);
    }

    public function create(Course $course,Module $module): View{
        $this->ensureModuleBelongsToCourse($course,$module);

        $suggestedSortOrder=((int)$module->lessons()->max('sort_order'))+1;

        return view('content.lessons.create',[
            'course'=>$course,
            'module'=>$module,
            'routePrefix'=>$this->routePrefix(),
            'suggestedSortOrder'=>max(1,$suggestedSortOrder),
            'maxUploadHuman'=>SiteSetting::uploadLimitHuman()
        ]);
    }

    public function store(Request $request,Course $course,Module $module): RedirectResponse{
        $this->ensureModuleBelongsToCourse($course,$module);

        $validated=$this->validatedData($request);

        $lesson=Lesson::create([
            'module_id'=>$module->id,
            'title'=>$validated['title'],
            'slug'=>$this->uniqueSlug($module,$validated['title']),
            'summary'=>$validated['summary']??null,
            'content'=>$validated['content']??null,
            'references'=>$validated['references']??null,
            'estimated_minutes'=>$validated['estimated_minutes']??null,
            'sort_order'=>$validated['sort_order'],
            'status'=>$validated['status']==='published'?'published':'draft'
        ]);

        $this->saveResources($request,$lesson);

        return redirect()
            ->route($this->routePrefix().'.courses.modules.lessons.index',[$course,$module])
            ->with('status','La lección fue creada correctamente.');
    }

    public function edit(Course $course,Module $module,Lesson $lesson): View{
        $this->ensureLessonHierarchy($course,$module,$lesson);

        $lesson->load([
            'resources'=>fn($q)=>$q->orderBy('sort_order')->orderBy('id')
        ]);

        return view('content.lessons.edit',[
            'course'=>$course,
            'module'=>$module,
            'lesson'=>$lesson,
            'routePrefix'=>$this->routePrefix(),
            'suggestedSortOrder'=>$lesson->sort_order,
            'maxUploadHuman'=>SiteSetting::uploadLimitHuman()
        ]);
    }

    public function update(Request $request,Course $course,Module $module,Lesson $lesson): RedirectResponse{
        $this->ensureLessonHierarchy($course,$module,$lesson);

        $validated=$this->validatedData($request);

        $lesson->fill([
            'title'=>$validated['title'],
            'slug'=>$lesson->title===$validated['title']?$lesson->slug:$this->uniqueSlug($module,$validated['title'],$lesson->id),
            'summary'=>$validated['summary']??null,
            'content'=>$validated['content']??null,
            'references'=>$validated['references']??null,
            'estimated_minutes'=>$validated['estimated_minutes']??null,
            'sort_order'=>$validated['sort_order'],
            'status'=>$validated['status']==='published'?'published':'draft'
        ]);

        $lesson->save();

        $this->saveResources($request,$lesson);

        return redirect()
            ->route($this->routePrefix().'.courses.modules.lessons.edit',[$course,$module,$lesson])
            ->with('status','La lección fue actualizada correctamente. Estado actual: '.($lesson->status==='published'?'Publicado':'Borrador').'.');
    }

    public function destroy(Course $course,Module $module,Lesson $lesson): RedirectResponse{
        $this->ensureLessonHierarchy($course,$module,$lesson);

        Storage::disk('public')->deleteDirectory('lesson-resources/'.$lesson->id);

        $lesson->delete();

        return redirect()
            ->route($this->routePrefix().'.courses.modules.lessons.index',[$course,$module])
            ->with('status','La lección fue eliminada.');
    }

    public function destroyResource(Course $course,Module $module,Lesson $lesson,LessonResource $resource): RedirectResponse{
        $this->ensureLessonHierarchy($course,$module,$lesson);

        abort_unless($resource->lesson_id===$lesson->id,404);

        if($resource->source_type==='upload'&&$resource->file_path){
            Storage::disk('public')->delete($resource->file_path);
        }

        $resource->delete();

        return back()->with('status','El recurso fue eliminado.');
    }

    private function validatedData(Request $request): array{
        $maxUploadKb=SiteSetting::uploadLimitKb();
        $maxUploadHuman=SiteSetting::uploadLimitHuman();

        return $request->validate([
            'title'=>['required','string','max:255'],
            'summary'=>['nullable','string','max:1000'],
            'content'=>['nullable','string','max:50000'],
            'references'=>['nullable','string','max:50000'],
            'estimated_minutes'=>['nullable','integer','min:1','max:600'],
            'status'=>['required',Rule::in(['draft','published'])],
            'sort_order'=>['required','integer','min:0','max:9999'],

            'resource_files'=>['nullable','array','max:10'],
            'resource_files.*'=>[
                'file',
                'max:'.$maxUploadKb,
                'mimes:mp4,mov,webm,m4v,jpg,jpeg,png,gif,webp,svg,pdf,mp3,wav,m4a,aac,ogg,flac'
            ],

            'resource_blocks'=>['nullable','array','max:30'],
            'resource_blocks.*.type'=>['nullable',Rule::in(['text','video','link','pdf','image'])],
            'resource_blocks.*.title'=>['nullable','string','max:255'],
            'resource_blocks.*.content'=>['nullable','string','max:50000'],
            'resource_blocks.*.url'=>['nullable','url','max:2000'],
            'resource_blocks.*.file'=>[
                'nullable',
                'file',
                'max:'.$maxUploadKb,
                'mimes:mp4,mov,webm,m4v,jpg,jpeg,png,gif,webp,svg,pdf'
            ],

            'external_resources'=>[
                'nullable',
                'string',
                'max:20000',
                function($attribute,$value,$fail){
                    $urls=array_values(array_filter(array_map('trim',preg_split('/\r\n|\r|\n/',$value))));

                    if(count($urls)>10){
                        $fail('Puedes agregar como máximo 10 enlaces externos a la vez.');
                        return;
                    }

                    foreach($urls as $url){
                        $scheme=strtolower(parse_url($url,PHP_URL_SCHEME)??'');

                        if(!filter_var($url,FILTER_VALIDATE_URL)||!in_array($scheme,['http','https'],true)){
                            $fail('Cada recurso externo debe contener una URL válida con http o https.');
                            return;
                        }
                    }
                }
            ]
        ],[
            'title.required'=>'El título de la lección es obligatorio.',
            'status.required'=>'Debes seleccionar si la lección será borrador o publicada.',
            'status.in'=>'El estado de la lección no es válido.',
            'sort_order.required'=>'El orden de la lección es obligatorio.',

            'resource_files.max'=>'Puedes seleccionar como máximo 10 archivos a la vez.',
            'resource_files.*.max'=>'El archivo excede el límite permitido. Máximo: '.$maxUploadHuman.'.',
            'resource_files.*.mimes'=>'El archivo seleccionado no tiene un formato permitido. Puedes subir MP4, MOV, WEBM, M4V, imágenes, PDF o audio.',

            'resource_blocks.max'=>'Puedes agregar como máximo 30 recursos por envío.',
            'resource_blocks.*.type.in'=>'El tipo de recurso seleccionado no es válido.',
            'resource_blocks.*.url.url'=>'El enlace del recurso debe ser una URL válida.',
            'resource_blocks.*.file.max'=>'El archivo excede el límite permitido. Máximo: '.$maxUploadHuman.'.',
            'resource_blocks.*.file.mimes'=>'El archivo seleccionado no tiene un formato permitido. Para videos usa MP4, MOV, WEBM o M4V; para imágenes usa JPG, PNG, GIF, WEBP o SVG; para documentos usa PDF.'
        ]);
    }

    private function saveResources(Request $request,Lesson $lesson): void{
        $order=((int)$lesson->resources()->max('sort_order'))+1;

        foreach($request->input('resource_blocks',[]) as $index=>$block){
            $type=$block['type']??null;
            $title=$block['title']??null;
            $content=$block['content']??null;
            $url=$block['url']??null;
            $file=$request->file("resource_blocks.$index.file");

            if(!$type)continue;

            if($type==='text'){
                if(!trim(strip_tags((string)$content)))continue;

                LessonResource::create([
                    'lesson_id'=>$lesson->id,
                    'title'=>$title ?: 'Bloque de texto',
                    'resource_type'=>'text',
                    'source_type'=>'text',
                    'file_path'=>null,
                    'external_url'=>null,
                    'content'=>$content,
                    'caption'=>null,
                    'mime_type'=>null,
                    'original_name'=>null,
                    'file_size'=>null,
                    'sort_order'=>$order++
                ]);

                continue;
            }

            if($file){
                $path=$file->store('lesson-resources/'.$lesson->id,'public');

                LessonResource::create([
                    'lesson_id'=>$lesson->id,
                    'title'=>$title ?: pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME),
                    'resource_type'=>$this->detectFileType($file),
                    'source_type'=>'upload',
                    'file_path'=>$path,
                    'external_url'=>null,
                    'content'=>null,
                    'caption'=>null,
                    'mime_type'=>$file->getMimeType(),
                    'original_name'=>$file->getClientOriginalName(),
                    'file_size'=>$file->getSize(),
                    'sort_order'=>$order++
                ]);

                continue;
            }

            if($url){
                LessonResource::create([
                    'lesson_id'=>$lesson->id,
                    'title'=>$title ?: $this->externalTitle($url),
                    'resource_type'=>$this->detectUrlType($url),
                    'source_type'=>'external',
                    'file_path'=>null,
                    'external_url'=>$url,
                    'content'=>null,
                    'caption'=>null,
                    'mime_type'=>null,
                    'original_name'=>null,
                    'file_size'=>null,
                    'sort_order'=>$order++
                ]);
            }
        }

        foreach($request->file('resource_files',[]) as $file){
            $path=$file->store('lesson-resources/'.$lesson->id,'public');

            LessonResource::create([
                'lesson_id'=>$lesson->id,
                'title'=>pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME),
                'resource_type'=>$this->detectFileType($file),
                'source_type'=>'upload',
                'file_path'=>$path,
                'external_url'=>null,
                'content'=>null,
                'caption'=>null,
                'mime_type'=>$file->getMimeType(),
                'original_name'=>$file->getClientOriginalName(),
                'file_size'=>$file->getSize(),
                'sort_order'=>$order++
            ]);
        }

        $urls=array_values(array_filter(array_map('trim',preg_split('/\r\n|\r|\n/',$request->input('external_resources','')))));

        foreach($urls as $url){
            LessonResource::create([
                'lesson_id'=>$lesson->id,
                'title'=>$this->externalTitle($url),
                'resource_type'=>$this->detectUrlType($url),
                'source_type'=>'external',
                'file_path'=>null,
                'external_url'=>$url,
                'content'=>null,
                'caption'=>null,
                'mime_type'=>null,
                'original_name'=>null,
                'file_size'=>null,
                'sort_order'=>$order++
            ]);
        }
    }

    private function detectFileType(UploadedFile $file): string{
        $extension=strtolower($file->getClientOriginalExtension());
        $mime=strtolower($file->getMimeType()??'');

        if($extension==='svg')return 'image';
        if($extension==='pdf'||$mime==='application/pdf')return 'pdf';
        if(str_starts_with($mime,'video/')||in_array($extension,['mp4','mov','webm','m4v'],true))return 'video';
        if(str_starts_with($mime,'audio/')||in_array($extension,['mp3','wav','m4a','aac','ogg','flac'],true))return 'audio';
        if(str_starts_with($mime,'image/')||in_array($extension,['jpg','jpeg','png','gif','webp','svg'],true))return 'image';

        return 'file';
    }

    private function detectUrlType(string $url): string{
        $host=strtolower(parse_url($url,PHP_URL_HOST)??'');
        $path=strtolower(parse_url($url,PHP_URL_PATH)??'');
        $extension=strtolower(pathinfo($path,PATHINFO_EXTENSION));

        if(str_contains($host,'youtube.com')||$host==='youtu.be'||str_contains($host,'vimeo.com'))return 'video';
        if(in_array($extension,['mp4','mov','webm','m4v'],true))return 'video';
        if(in_array($extension,['mp3','wav','m4a','aac','ogg','flac'],true))return 'audio';
        if(in_array($extension,['jpg','jpeg','png','gif','webp','svg'],true))return 'image';
        if($extension==='pdf')return 'pdf';

        return 'link';
    }

    private function externalTitle(string $url): string{
        $path=trim(parse_url($url,PHP_URL_PATH)??'','/');
        $name=$path!==''?urldecode(basename($path)):null;

        if($name&&$name!=='watch')return $name;

        return parse_url($url,PHP_URL_HOST)??'Recurso externo';
    }

    private function ensureModuleBelongsToCourse(Course $course,Module $module): void{
        abort_unless($module->course_id===$course->id,404);
    }

    private function ensureLessonHierarchy(Course $course,Module $module,Lesson $lesson): void{
        $this->ensureModuleBelongsToCourse($course,$module);

        abort_unless($lesson->module_id===$module->id,404);
    }

    private function routePrefix(): string{
        return auth()->user()->isAdmin()?'admin':'gestor';
    }

    private function uniqueSlug(Module $module,string $title,?int $ignoreId=null): string{
        $base=Str::slug($title)?:'leccion';
        $slug=$base;
        $number=2;

        while(
            Lesson::where('module_id',$module->id)
                ->where('slug',$slug)
                ->when($ignoreId,fn($q)=>$q->where('id','!=',$ignoreId))
                ->exists()
        ){
            $slug=$base.'-'.$number++;
        }

        return $slug;
    }
}