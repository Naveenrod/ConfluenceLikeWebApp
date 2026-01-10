<div class="quill-editor">
    <div id="{{ $id ?? 'content' }}-editor" style="height: 400px;"></div>
    <textarea id="{{ $id ?? 'content' }}" name="{{ $name ?? 'content' }}" style="display: none;">{{ $value ?? '' }}</textarea>
</div>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .quill-editor .ql-toolbar {
        background-color: #161a1d;
        border-color: #a6c5e229 !important;
        border-radius: 8px 8px 0 0;
    }
    .quill-editor .ql-toolbar .ql-stroke {
        stroke: #8c9bab;
    }
    .quill-editor .ql-toolbar .ql-fill {
        fill: #8c9bab;
    }
    .quill-editor .ql-toolbar .ql-picker {
        color: #8c9bab;
    }
    .quill-editor .ql-toolbar .ql-picker-options {
        background-color: #22272b;
        border-color: #a6c5e229;
    }
    .quill-editor .ql-toolbar button:hover .ql-stroke,
    .quill-editor .ql-toolbar button.ql-active .ql-stroke {
        stroke: #579dff;
    }
    .quill-editor .ql-toolbar button:hover .ql-fill,
    .quill-editor .ql-toolbar button.ql-active .ql-fill {
        fill: #579dff;
    }
    .quill-editor .ql-container {
        background-color: #22272b;
        border-color: #a6c5e229 !important;
        border-radius: 0 0 8px 8px;
        font-size: 14px;
    }
    .quill-editor .ql-editor {
        color: #b6c2cf;
        min-height: 300px;
    }
    .quill-editor .ql-editor.ql-blank::before {
        color: #8c9bab;
        font-style: normal;
    }
    .quill-editor .ql-editor h1,
    .quill-editor .ql-editor h2,
    .quill-editor .ql-editor h3 {
        color: white;
    }
    .quill-editor .ql-editor a {
        color: #579dff;
    }
    .quill-editor .ql-editor pre {
        background-color: #161a1d;
        color: #b6c2cf;
        border-radius: 4px;
    }
    .quill-editor .ql-editor blockquote {
        border-left-color: #579dff;
        color: #8c9bab;
    }
    .quill-editor .ql-snow .ql-tooltip {
        background-color: #22272b;
        border-color: #a6c5e229;
        color: #b6c2cf;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }
    .quill-editor .ql-snow .ql-tooltip input[type=text] {
        background-color: #161a1d;
        border-color: #a6c5e229;
        color: #b6c2cf;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editorId = '{{ $id ?? 'content' }}';
    const editorElement = document.getElementById(editorId + '-editor');
    const textareaElement = document.getElementById(editorId);

    if (!editorElement) return;

    const quill = new Quill(editorElement, {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'color': [] }, { 'background': [] }],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': [] }],
                ['link', 'image'],
                ['blockquote', 'code-block'],
                ['clean']
            ]
        },
        placeholder: 'Start writing your content...',
    });

    if (textareaElement.value) {
        quill.root.innerHTML = textareaElement.value;
    }

    quill.on('text-change', function() {
        textareaElement.value = quill.root.innerHTML;
    });

    const form = textareaElement.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            textareaElement.value = quill.root.innerHTML;
        });
    }
});
</script>
@endpush
