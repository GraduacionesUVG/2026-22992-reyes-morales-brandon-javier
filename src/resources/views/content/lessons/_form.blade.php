<div class="cms-form-grid">
    <div class="cms-field cms-field-full">
        <label>Módulo</label>
        <input type="text" value="{{ $module->title }}" disabled>
        <small>La lección pertenecerá a este módulo.</small>
    </div>

    <div class="cms-field cms-field-full">
        <label for="title">Título de la lección</label>
        <input id="title" name="title" type="text" value="{{ old('title',$lesson->title ?? '') }}" placeholder="Ej. Licencia tipo C" required autofocus>
        @error('title')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="summary">Resumen</label>
        <textarea id="summary" name="summary" rows="3" placeholder="Describe brevemente qué aprenderá el estudiante.">{{ old('summary',$lesson->summary ?? '') }}</textarea>
        @error('summary')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label>Contenido principal</label>
        <input type="hidden" id="content" name="content" value="{{ old('content',$lesson->content ?? '') }}">
        <div class="rich-editor">
            <div class="rich-toolbar">
                <button type="button" data-cmd="bold"><b>B</b></button>
                <button type="button" data-cmd="italic"><i>I</i></button>
                <button type="button" data-cmd="underline"><u>U</u></button>
                <button type="button" data-cmd="insertUnorderedList">• Lista</button>
                <button type="button" data-cmd="insertOrderedList">1. Lista</button>
                <button type="button" data-format="h2">Título</button>
                <button type="button" data-format="h3">Subtítulo</button>
                <button type="button" data-size="small">Pequeño</button>
                <button type="button" data-size="normal">Normal</button>
                <button type="button" data-size="large">Grande</button>
            </div>
            <div id="content_editor" class="rich-area" contenteditable="true">{!! old('content',$lesson->content ?? '') !!}</div>
        </div>
        <small>Puedes usar negrita, cursiva, subrayado, viñetas, listas y tamaños de texto.</small>
        @error('content')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label>Referencias o citas</label>
        <input type="hidden" id="references" name="references" value="{{ old('references',$lesson->references ?? '') }}">
        <div class="rich-editor">
            <div class="rich-toolbar">
                <button type="button" data-target="references_editor" data-cmd="bold"><b>B</b></button>
                <button type="button" data-target="references_editor" data-cmd="italic"><i>I</i></button>
                <button type="button" data-target="references_editor" data-cmd="underline"><u>U</u></button>
                <button type="button" data-target="references_editor" data-cmd="insertUnorderedList">• Lista</button>
                <button type="button" data-target="references_editor" data-cmd="insertOrderedList">1. Lista</button>
            </div>
            <div id="references_editor" class="rich-area rich-area-small" contenteditable="true">{!! old('references',$lesson->references ?? '') !!}</div>
        </div>
        <small>Ejemplo: Ley de Tránsito y su Reglamento, artículo 14. / Departamento de Tránsito PNC.</small>
        @error('references')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="estimated_minutes">Duración estimada</label>
        <input id="estimated_minutes" name="estimated_minutes" type="number" min="1" max="600" value="{{ old('estimated_minutes',$lesson->estimated_minutes ?? '') }}" placeholder="10">
        <small>Tiempo aproximado en minutos.</small>
        @error('estimated_minutes')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field">
        <label for="sort_order">Orden</label>
        <input id="sort_order" name="sort_order" type="number" min="0" max="9999" value="{{ old('sort_order',$lesson->sort_order ?? $suggestedSortOrder) }}" required>
        <small>Define la posición dentro del módulo.</small>
        @error('sort_order')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="status">Estado</label>
        <select id="status" name="status" required>
            <option value="draft" @selected(old('status',$lesson->status ?? 'draft')==='draft')>Borrador</option>
            <option value="published" @selected(old('status',$lesson->status ?? 'draft')==='published')>Publicado</option>
        </select>
        <small>Solo las lecciones publicadas serán visibles para estudiantes.</small>
        @error('status')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label>Recursos de la lección</label>
        <div id="resourceBlocks" class="resource-blocks"></div>
        <button type="button" id="addResourceBtn" class="cms-secondary-button">+ Agregar recurso</button>
        <small>Puedes agregar varios recursos: texto, video, link, PDF o imagen. Para video puedes subir MP4/MOV/WEBM/M4V o usar enlace externo. Máximo por archivo: <strong>{{ $maxUploadHuman }}</strong>.</small>
        @error('resource_blocks')<p class="cms-field-error">{{ $message }}</p>@enderror
        @error('resource_blocks.*.file')<p class="cms-field-error">{{ $message }}</p>@enderror
        @error('resource_blocks.*.url')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    @if(isset($lesson)&&$lesson->resources->isNotEmpty())
        <div class="cms-field cms-field-full">
            <label>Recursos existentes</label>
            <div class="existing-resources">
                @foreach($lesson->resources as $resource)
                    <div class="existing-resource-item">
                        <div>
                            <strong>{{ $resource->title ?: 'Recurso' }}</strong>
                            <small>
                                {{ strtoupper($resource->resource_type) }} ·
                                {{ $resource->source_type==='upload'?'Archivo subido':'Enlace / texto' }}
                                @if($resource->file_size)
                                    · {{ number_format($resource->file_size/1024/1024,2) }} MB
                                @endif
                            </small>
                        </div>
                        <button type="submit"
                            form="delete-resource-{{ $resource->id }}"
                            class="cms-danger-button"
                            onclick="return confirm('¿Eliminar este recurso?')">
                            Eliminar
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="cms-field cms-field-full">
        <label for="resource_files">Carga rápida de archivos <span class="cms-optional">Opcional</span></label>
        <input id="resource_files" name="resource_files[]" type="file" multiple accept=".mp4,.mov,.webm,.m4v,.jpg,.jpeg,.png,.gif,.webp,.svg,.pdf,.mp3,.wav,.m4a,.aac,.ogg,.flac">
        <small>Esta opción permite subir varios archivos de una sola vez. Máximo por archivo: <strong>{{ $maxUploadHuman }}</strong>.</small>
        @error('resource_files')<p class="cms-field-error">{{ $message }}</p>@enderror
        @error('resource_files.*')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>

    <div class="cms-field cms-field-full">
        <label for="external_resources">Carga rápida de enlaces <span class="cms-optional">Opcional</span></label>
        <textarea id="external_resources" name="external_resources" rows="4" placeholder="https://youtube.com/watch?v=...&#10;https://sitio.com/manual.pdf">{{ old('external_resources') }}</textarea>
        <small>Agrega un enlace por línea.</small>
        @error('external_resources')<p class="cms-field-error">{{ $message }}</p>@enderror
    </div>
</div>

@push('styles')
<style>
.rich-editor{border:1px solid rgba(15,23,42,.14);border-radius:18px;background:#fff;overflow:hidden}
.rich-toolbar{display:flex;gap:6px;flex-wrap:wrap;padding:10px;border-bottom:1px solid rgba(15,23,42,.08);background:#f8fafc}
.rich-toolbar button{border:1px solid rgba(15,23,42,.12);background:#fff;border-radius:10px;padding:7px 10px;cursor:pointer;font-size:13px}
.rich-toolbar button:hover{background:#eef2ff}
.rich-area{min-height:260px;padding:16px;outline:none;line-height:1.7}
.rich-area-small{min-height:140px}
.rich-area h2{font-size:1.6rem;margin:1rem 0 .5rem}
.rich-area h3{font-size:1.25rem;margin:1rem 0 .5rem}
.rich-area .text-small{font-size:.9rem}
.rich-area .text-large{font-size:1.25rem}
.rich-area ul,.rich-area ol{padding-left:1.6rem;margin:.8rem 0}
.rich-area ul{list-style:disc}
.rich-area ol{list-style:decimal}
.rich-area li{margin:.35rem 0}
.resource-blocks{display:flex;flex-direction:column;gap:16px;margin-bottom:14px}
.resource-block{border:1px solid rgba(15,23,42,.12);border-radius:18px;padding:16px;background:#f8fafc}
.resource-block-grid{display:grid;grid-template-columns:180px 1fr;gap:12px}
.resource-extra{grid-column:1/-1}
.resource-source-options{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:12px}
.resource-source-options label{display:flex;align-items:center;gap:8px;border:1px solid rgba(15,23,42,.12);background:#fff;border-radius:12px;padding:9px 12px;cursor:pointer;font-weight:700}
.resource-source-options input{width:auto;margin:0}
.resource-help{display:block;margin-top:8px;color:#64748b}
.existing-resources{display:flex;flex-direction:column;gap:10px}
.existing-resource-item{display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px solid rgba(15,23,42,.1);border-radius:14px;padding:12px;background:#fff}
.cms-danger-button{border:0;border-radius:10px;background:#fee2e2;color:#991b1b;padding:9px 12px;cursor:pointer}
.cms-danger-button:hover{filter:brightness(.98)}
@media(max-width:760px){.resource-block-grid{grid-template-columns:1fr}}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded',()=>{
    const form=document.querySelector('form.cms-form-card');
    const maxUploadMb={{ (int) str_replace(' MB','',$maxUploadHuman) }};
    const maxUploadBytes=maxUploadMb*1024*1024;

    const syncEditors=()=>{
        const content=document.getElementById('content');
        const contentEditor=document.getElementById('content_editor');
        const references=document.getElementById('references');
        const referencesEditor=document.getElementById('references_editor');

        if(content&&contentEditor)content.value=contentEditor.innerHTML;
        if(references&&referencesEditor)references.value=referencesEditor.innerHTML;

        document.querySelectorAll('[data-resource-editor]').forEach(editor=>{
            let input=document.getElementById(editor.dataset.input);
            if(input)input.value=editor.innerHTML;
        });
    };

    const runEditorCommand=(cmd)=>{
        if(cmd==='insertUnorderedList')document.execCommand('insertUnorderedList',false,null);
        else if(cmd==='insertOrderedList')document.execCommand('insertOrderedList',false,null);
        else document.execCommand(cmd,false,null);
    };

    document.querySelectorAll('.rich-toolbar button').forEach(btn=>{
        btn.addEventListener('click',()=>{
            const targetId=btn.dataset.target||'content_editor';
            const editor=document.getElementById(targetId);

            if(!editor)return;

            editor.focus();

            if(btn.dataset.cmd)runEditorCommand(btn.dataset.cmd);
            if(btn.dataset.format)document.execCommand('formatBlock',false,btn.dataset.format);

            if(btn.dataset.size){
                const cls=btn.dataset.size==='small'?'text-small':btn.dataset.size==='large'?'text-large':'';
                document.execCommand('fontSize',false,'4');

                editor.querySelectorAll('font[size="4"]').forEach(el=>{
                    const span=document.createElement('span');
                    span.innerHTML=el.innerHTML;
                    if(cls)span.className=cls;
                    el.replaceWith(span);
                });
            }

            syncEditors();
        });
    });

    document.querySelectorAll('.rich-area').forEach(el=>el.addEventListener('input',syncEditors));

    const validateFilesBeforeSubmit=()=>{
        let valid=true;
        let message='';

        document.querySelectorAll('input[type="file"]').forEach(input=>{
            Array.from(input.files||[]).forEach(file=>{
                if(file.size>maxUploadBytes){
                    valid=false;
                    message=`El archivo "${file.name}" pesa ${(file.size/1024/1024).toFixed(2)} MB y excede el límite permitido. Máximo: ${maxUploadMb} MB.`;
                }
            });
        });

        if(!valid)alert(message);
        return valid;
    };

    form?.addEventListener('submit',e=>{
        syncEditors();

        if(!validateFilesBeforeSubmit()){
            e.preventDefault();
            e.stopPropagation();
        }
    });

    let resourceIndex=0;
    const wrapper=document.getElementById('resourceBlocks');
    const addBtn=document.getElementById('addResourceBtn');

    const bindResourceToolbar=(extra)=>{
        extra.querySelectorAll('[data-resource-target]').forEach(btn=>{
            btn.addEventListener('click',()=>{
                const editor=document.getElementById(btn.dataset.resourceTarget);
                if(!editor)return;

                editor.focus();
                runEditorCommand(btn.dataset.cmd);
                syncEditors();
            });
        });

        const editor=extra.querySelector('[data-resource-editor]');
        if(editor)editor.addEventListener('input',syncEditors);
    };

    const renderTextResource=(extra,index)=>{
        extra.innerHTML=`<input type="hidden" id="resource_content_${index}" name="resource_blocks[${index}][content]"><label>Contenido del bloque</label><div class="rich-editor"><div class="rich-toolbar"><button type="button" data-resource-target="resource_editor_${index}" data-cmd="bold"><b>B</b></button><button type="button" data-resource-target="resource_editor_${index}" data-cmd="italic"><i>I</i></button><button type="button" data-resource-target="resource_editor_${index}" data-cmd="underline"><u>U</u></button><button type="button" data-resource-target="resource_editor_${index}" data-cmd="insertUnorderedList">• Lista</button><button type="button" data-resource-target="resource_editor_${index}" data-cmd="insertOrderedList">1. Lista</button></div><div id="resource_editor_${index}" data-resource-editor data-input="resource_content_${index}" class="rich-area rich-area-small" contenteditable="true"></div></div>`;
        bindResourceToolbar(extra);
    };

    const renderVideoResource=(extra,index)=>{
        extra.innerHTML=`<label>Origen del video</label><div class="resource-source-options"><label><input type="radio" name="resource_blocks[${index}][video_source]" value="upload" checked> Subir video</label><label><input type="radio" name="resource_blocks[${index}][video_source]" value="url"> Usar enlace</label></div><div class="video-source-content"></div>`;

        const content=extra.querySelector('.video-source-content');

        const renderUpload=()=>{
            content.innerHTML=`<label>Archivo de video</label><input name="resource_blocks[${index}][file]" type="file" accept=".mp4,.mov,.webm,.m4v"><small class="resource-help">Formatos permitidos: MP4, MOV, WEBM o M4V. Máximo: {{ $maxUploadHuman }}.</small>`;
        };

        const renderUrl=()=>{
            content.innerHTML=`<label>URL del video</label><input name="resource_blocks[${index}][url]" type="url" placeholder="https://youtube.com/watch?v=... o https://vimeo.com/..."><small class="resource-help">Puedes usar YouTube, Vimeo o un enlace directo a video.</small>`;
        };

        renderUpload();

        extra.querySelectorAll(`input[name="resource_blocks[${index}][video_source]"]`).forEach(radio=>{
            radio.addEventListener('change',()=>{
                if(radio.value==='upload'&&radio.checked)renderUpload();
                if(radio.value==='url'&&radio.checked)renderUrl();
            });
        });
    };

    const renderLinkResource=(extra,index)=>{
        extra.innerHTML=`<label>URL de referencia</label><input name="resource_blocks[${index}][url]" type="url" placeholder="https://..."><small class="resource-help">Agrega un sitio web, documento externo o referencia.</small>`;
    };

    const renderPdfResource=(extra,index)=>{
        extra.innerHTML=`<label>Archivo PDF</label><input name="resource_blocks[${index}][file]" type="file" accept=".pdf"><small class="resource-help">Sube un archivo PDF. Máximo: {{ $maxUploadHuman }}.</small>`;
    };

    const renderImageResource=(extra,index)=>{
        extra.innerHTML=`<label>Archivo de imagen</label><input name="resource_blocks[${index}][file]" type="file" accept=".jpg,.jpeg,.png,.gif,.webp,.svg"><small class="resource-help">Formatos permitidos: JPG, PNG, GIF, WEBP o SVG. Máximo: {{ $maxUploadHuman }}.</small>`;
    };

    const renderExtra=(block,type,index)=>{
        const extra=block.querySelector('.resource-extra');
        if(!extra)return;

        if(type==='text')renderTextResource(extra,index);
        else if(type==='video')renderVideoResource(extra,index);
        else if(type==='link')renderLinkResource(extra,index);
        else if(type==='pdf')renderPdfResource(extra,index);
        else if(type==='image')renderImageResource(extra,index);
        else extra.innerHTML='';
    };

    addBtn?.addEventListener('click',()=>{
        const index=resourceIndex++;
        const block=document.createElement('div');

        block.className='resource-block';
        block.innerHTML=`<div class="resource-block-grid"><div><label>Tipo</label><select name="resource_blocks[${index}][type]" required><option value="text">Bloque de texto</option><option value="video">Video</option><option value="link">Link de referencia</option><option value="pdf">PDF</option><option value="image">Imagen</option></select></div><div><label>Título del recurso</label><input name="resource_blocks[${index}][title]" type="text" placeholder="Ej. Video explicativo, fuente legal, imagen de apoyo..."></div><div class="resource-extra"></div><div class="resource-extra"><button type="button" class="cms-danger-button remove-resource">Quitar recurso</button></div></div>`;

        wrapper.appendChild(block);

        const select=block.querySelector('select');
        renderExtra(block,select.value,index);

        select.addEventListener('change',()=>renderExtra(block,select.value,index));
        block.querySelector('.remove-resource').addEventListener('click',()=>block.remove());
    });
});
</script>
@endpush