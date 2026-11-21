@extends('layouts.app')

@section('title',$lesson->title.' · '.config('branding.site_name'))

@section('page_title','Lección')

@section('page_description',$lesson->module->title)

@section('content')
<div class="lesson-page">

    <a href="{{ route('student.modules.show',$lesson->module) }}" class="module-back">
        <svg viewBox="0 0 20 20" fill="none">
            <path d="M16 10H4M9 5L4 10L9 15" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
        Volver al módulo
    </a>

    @if(session('status'))
        <div class="cms-alert-success">
            <span>✓</span>
            {{ session('status') }}
        </div>
    @endif

    <header class="lesson-page-heading">
        <div class="lesson-page-kicker">
            <span>{{ $lesson->module->title }}</span>

            @if($position)
                <span>Lección {{ $position }} de {{ $totalLessons }}</span>
            @endif

            @if($lesson->estimated_minutes)
                <span>{{ $lesson->estimated_minutes }} min</span>
            @endif

            @if($isCompleted)
                <span>Completada</span>
            @endif
        </div>

        <h1>{{ $lesson->title }}</h1>

        @if($lesson->summary)
            <p>{{ $lesson->summary }}</p>
        @endif

        <form method="POST" action="{{ route('student.favorites.toggle',$lesson) }}" style="margin-top:18px;">
            @csrf
            <button type="submit" class="{{ $isFavorite?'cms-secondary-button':'cms-primary-button' }}">
                {{ $isFavorite?'Quitar de favoritos':'Agregar a favoritos' }}
            </button>
        </form>
    </header>

    @if($lesson->content)
        <section class="lesson-content-card">
            <div class="lesson-resource-heading">
                <span>Contenido principal</span>
                <h2>Desarrollo de la lección</h2>
            </div>

            <div class="lesson-content-text rich-lesson-content">
                {!! $lesson->content !!}
            </div>
        </section>
    @endif

    @if($lesson->references)
        <section class="lesson-content-card">
            <div class="lesson-resource-heading">
                <span>Referencias</span>
                <h2>Fuentes o citas de esta lección</h2>
            </div>

            <div class="lesson-content-text rich-lesson-content lesson-references">
                {!! $lesson->references !!}
            </div>
        </section>
    @endif

    <section class="lesson-material-card">
        <div class="lesson-file-resource">
            <div>
                <strong>
                    {{ $isCompleted ? 'Lección completada' : '¿Terminaste esta lección?' }}
                </strong>

                <span>
                    {{ $isCompleted ? 'Puedes volver a marcarla como pendiente si deseas repasarla.' : 'Márcala como completada para actualizar tu progreso.' }}
                </span>
            </div>

            @if($isCompleted)
                <form method="POST" action="{{ route('student.lessons.uncomplete',$lesson) }}">
                    @csrf
                    @method('DELETE')

                    <button type="submit" class="cms-secondary-button">
                        Marcar pendiente
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('student.lessons.complete',$lesson) }}">
                    @csrf

                    <button type="submit" class="cms-primary-button">
                        Marcar como completada
                    </button>
                </form>
            @endif
        </div>
    </section>

    @if($lesson->resources->isNotEmpty())
        <section class="lesson-materials">
            <div class="lesson-materials-heading">
                <span>Material de apoyo</span>
                <h2>Recursos para esta lección</h2>
            </div>

            @foreach($lesson->resources as $resource)
                @php
                    $url=$resource->url();
                    $embed=$resource->embedUrl();
                    $typeLabel=[
                        'text'=>'Texto',
                        'video'=>'Video',
                        'image'=>'Imagen',
                        'audio'=>'Audio',
                        'pdf'=>'PDF',
                        'link'=>'Enlace',
                        'file'=>'Archivo',
                        'vector'=>'Vector'
                    ][$resource->resource_type] ?? strtoupper($resource->resource_type);
                @endphp

                <article class="lesson-material-card">
                    <div class="lesson-resource-heading">
                        <span>{{ $typeLabel }}</span>

                        <h2>{{ $resource->title ?: 'Recurso de apoyo' }}</h2>

                        @if($resource->caption)
                            <p>{{ $resource->caption }}</p>
                        @endif
                    </div>

                    @if($resource->resource_type==='text')
                        <div class="lesson-content-text rich-lesson-content">
                            {!! $resource->content !!}
                        </div>

                    @elseif($resource->resource_type==='video')
                        @if($embed)
                            <div class="lesson-video-wrap">
                                <iframe src="{{ $embed }}" title="{{ $resource->title ?: 'Video de apoyo' }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            </div>

                            @if($url)
                                <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-resource-open">
                                    Abrir video ↗
                                </a>
                            @endif
                        @elseif($url)
                            <video class="lesson-media-video" controls preload="metadata">
                                <source src="{{ $url }}" @if($resource->mime_type) type="{{ $resource->mime_type }}" @endif>
                                Tu navegador no puede reproducir este video.
                            </video>

                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-resource-open">
                                Abrir video ↗
                            </a>
                        @else
                            <p class="lesson-empty-resource">No se encontró el video asociado a este recurso.</p>
                        @endif

                    @elseif($resource->resource_type==='image')
                        @if($url)
                            <div class="lesson-image-wrap">
                                <img src="{{ $url }}" alt="{{ $resource->title ?: 'Imagen educativa' }}" loading="lazy">
                            </div>

                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-resource-open">
                                Abrir imagen ↗
                            </a>
                        @else
                            <p class="lesson-empty-resource">No se encontró la imagen asociada a este recurso.</p>
                        @endif

                    @elseif($resource->resource_type==='audio')
                        @if($url)
                            <div class="lesson-audio-wrap">
                                <audio controls preload="none" src="{{ $url }}">
                                    Tu navegador no puede reproducir este audio.
                                </audio>
                            </div>

                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-resource-open">
                                Abrir audio ↗
                            </a>
                        @else
                            <p class="lesson-empty-resource">No se encontró el audio asociado a este recurso.</p>
                        @endif

                    @elseif($resource->resource_type==='pdf')
                        @if($url)
                            <div class="lesson-pdf-wrap">
                                <iframe src="{{ $url }}" title="{{ $resource->title ?: 'Documento PDF' }}" loading="lazy"></iframe>
                            </div>

                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-resource-open">
                                Abrir PDF ↗
                            </a>
                        @else
                            <p class="lesson-empty-resource">No se encontró el PDF asociado a este recurso.</p>
                        @endif

                    @elseif($resource->resource_type==='vector')
                        @if($url)
                            <div class="lesson-file-resource">
                                <div>
                                    <strong>Archivo vectorial SVG</strong>
                                    <span>{{ $resource->original_name ?: $resource->title }}</span>
                                </div>

                                @if($resource->source_type==='upload')
                                    <a href="{{ $url }}" download="{{ $resource->original_name }}" class="cms-secondary-button">
                                        Descargar
                                    </a>
                                @else
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="cms-secondary-button">
                                        Abrir ↗
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="lesson-empty-resource">No se encontró el archivo vectorial asociado a este recurso.</p>
                        @endif

                    @elseif($resource->resource_type==='link')
                        @if($url)
                            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="lesson-external-resource">
                                <span>{{ $resource->title ?: 'Abrir enlace de referencia' }}</span>
                                <strong>↗</strong>
                            </a>
                        @else
                            <p class="lesson-empty-resource">No se encontró el enlace asociado a este recurso.</p>
                        @endif

                    @else
                        @if($url)
                            <div class="lesson-file-resource">
                                <div>
                                    <strong>{{ $resource->title ?: 'Archivo de apoyo' }}</strong>
                                    <span>{{ $resource->original_name ?: 'Recurso disponible' }}</span>
                                </div>

                                @if($resource->source_type==='upload')
                                    <a href="{{ $url }}" download="{{ $resource->original_name }}" class="cms-secondary-button">
                                        Descargar
                                    </a>
                                @else
                                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="cms-secondary-button">
                                        Abrir ↗
                                    </a>
                                @endif
                            </div>
                        @else
                            <p class="lesson-empty-resource">No se encontró el archivo asociado a este recurso.</p>
                        @endif
                    @endif
                </article>
            @endforeach
        </section>
    @endif

    @if($lesson->video_url)
        <section class="lesson-resource-card">
            <div class="lesson-resource-heading">
                <span>Recurso de apoyo</span>
                <h2>Video</h2>
            </div>

            @if($lesson->videoEmbedUrl())
                <div class="lesson-video-wrap">
                    <iframe src="{{ $lesson->videoEmbedUrl() }}" title="Video de apoyo: {{ $lesson->title }}" loading="lazy" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                </div>
            @else
                <a href="{{ $lesson->video_url }}" target="_blank" rel="noopener noreferrer" class="lesson-external-resource">
                    Abrir video de apoyo
                    <span>↗</span>
                </a>
            @endif
        </section>
    @endif

    <nav class="lesson-navigation" aria-label="Navegación entre lecciones">
        <div>
            @if($previousLesson)
                <a href="{{ route('student.lessons.show',$previousLesson) }}" class="lesson-nav-link is-previous">
                    <small>Anterior</small>
                    <strong>← {{ $previousLesson->title }}</strong>
                </a>
            @else
                <span></span>
            @endif
        </div>

        <div>
            @if($nextLesson)
                <a href="{{ route('student.lessons.show',$nextLesson) }}" class="lesson-nav-link is-next">
                    <small>Siguiente</small>
                    <strong>{{ $nextLesson->title }} →</strong>
                </a>
            @else
                <a href="{{ route('student.modules.show',$lesson->module) }}" class="lesson-nav-link is-next">
                    <small>Fin del módulo</small>
                    <strong>Volver al módulo →</strong>
                </a>
            @endif
        </div>
    </nav>
</div>
@endsection

@push('styles')
<style>
.rich-lesson-content{color:#1e293b;font-size:1rem;line-height:1.8}
.rich-lesson-content h1,.rich-lesson-content h2,.rich-lesson-content h3{color:#0f172a;line-height:1.25;margin:1.4rem 0 .7rem}
.rich-lesson-content h1{font-size:2rem}
.rich-lesson-content h2{font-size:1.55rem}
.rich-lesson-content h3{font-size:1.25rem}
.rich-lesson-content p{margin:.85rem 0}
.rich-lesson-content ul,.rich-lesson-content ol{padding-left:1.4rem;margin:.9rem 0}
.rich-lesson-content li{margin:.35rem 0}
.rich-lesson-content strong,.rich-lesson-content b{font-weight:750;color:#0f172a}
.rich-lesson-content em,.rich-lesson-content i{font-style:italic}
.rich-lesson-content u{text-underline-offset:3px}
.rich-lesson-content a{color:#2563eb;text-decoration:underline;text-underline-offset:3px}
.rich-lesson-content blockquote{border-left:4px solid #2563eb;background:#eff6ff;margin:1rem 0;padding:1rem 1.2rem;border-radius:0 14px 14px 0}
.rich-lesson-content .text-small{font-size:.9rem}
.rich-lesson-content .text-large{font-size:1.25rem}
.lesson-references{background:#f8fafc;border:1px solid rgba(15,23,42,.08);border-radius:16px;padding:16px}
.lesson-empty-resource{padding:14px 16px;border-radius:14px;background:#fef2f2;color:#991b1b;margin:0}
.lesson-resource-open{display:inline-flex;margin-top:14px;font-weight:700;color:#2563eb;text-decoration:none}
.lesson-resource-open:hover{text-decoration:underline}
.lesson-media-video{width:100%;max-height:560px;border-radius:20px;background:#020617}
.lesson-image-wrap{border-radius:20px;overflow:hidden;background:#f8fafc;border:1px solid rgba(15,23,42,.08)}
.lesson-image-wrap img{display:block;width:100%;height:auto}
.lesson-audio-wrap audio{width:100%}
.lesson-pdf-wrap{width:100%;height:620px;border:1px solid rgba(15,23,42,.1);border-radius:20px;overflow:hidden;background:#f8fafc}
.lesson-pdf-wrap iframe{width:100%;height:100%;border:0}
.rich-lesson-content ul,
.lesson-content-text ul{
    list-style:disc !important;
    padding-left:1.7rem !important;
    margin:1rem 0 !important;
}

.rich-lesson-content ol,
.lesson-content-text ol{
    list-style:decimal !important;
    padding-left:1.7rem !important;
    margin:1rem 0 !important;
}

.rich-lesson-content li,
.lesson-content-text li{
    margin:.4rem 0 !important;
    line-height:1.7 !important;
}
@media(max-width:760px){.lesson-pdf-wrap{height:460px}.rich-lesson-content h1{font-size:1.6rem}.rich-lesson-content h2{font-size:1.35rem}}
</style>
@endpush