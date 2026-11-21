<div style="padding:18px 22px 0;">
    <p class="cms-eyebrow">{{ $title }}</p>
</div>

@forelse($items as $item)
    @php
        $subtitle='';
        if($type==='modules')$subtitle=$item->course?->title;
        if($type==='lessons')$subtitle=($item->module?->title ?? 'Sin módulo').' · '.($item->module?->course?->title ?? 'Sin curso');
        if($type==='questions')$subtitle=($item->module?->title ?? 'Sin módulo').' · '.($item->module?->course?->title ?? 'Sin curso');
        $isPublished=$item->status==='published';
    @endphp

    <article class="course-admin-item">
        <div class="course-admin-main">
            <div class="course-admin-icon">{{ $loop->iteration }}</div>
            <div class="course-admin-info">
                <div class="course-admin-title-row">
                    <h2>{{ $type==='questions'?$item->question_text:$item->title }}</h2>
                    <span class="cms-status {{ $isPublished?'is-published':'is-draft' }}"><span></span>{{ $isPublished?'Publicado':'Borrador' }}</span>
                </div>

                @if($subtitle)
                    <p>{{ $subtitle }}</p>
                @elseif(isset($item->description) && $item->description)
                    <p>{{ $item->description }}</p>
                @elseif(isset($item->summary) && $item->summary)
                    <p>{{ $item->summary }}</p>
                @else
                    <p>Sin descripción adicional.</p>
                @endif

                @if($type!=='courses' && !$isPublished)
                    <div class="course-admin-meta">
                        <span>Nota: para que el estudiante lo vea, el curso, módulo y contenido relacionado también deben estar publicados.</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="course-admin-actions">
            <form method="POST" action="{{ route($routePrefix.'.review.status',[$type,$item->id]) }}">
                @csrf
                @method('PATCH')
                <input type="hidden" name="status" value="{{ $isPublished?'draft':'published' }}">
                <button type="submit" class="{{ $isPublished?'cms-secondary-button':'cms-primary-button' }}">
                    {{ $isPublished?'Pasar a borrador':'Publicar' }}
                </button>
            </form>
        </div>
    </article>
@empty
    <div class="cms-empty" style="padding:18px 22px;">
        <h2>{{ $empty }}</h2>
    </div>
@endforelse