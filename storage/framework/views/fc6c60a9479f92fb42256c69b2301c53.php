<div class="quill-editor">
    <div id="<?php echo e($id ?? 'content'); ?>-editor" style="height: 500px;"></div>
    <textarea id="<?php echo e($id ?? 'content'); ?>" name="<?php echo e($name ?? 'content'); ?>" style="display: none;"><?php echo e($value ?? ''); ?></textarea>
</div>

<?php $__env->startPush('styles'); ?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const editorId = '<?php echo e($id ?? 'content'); ?>';
    const editorElement = document.getElementById(editorId + '-editor');
    const textareaElement = document.getElementById(editorId);
    
    // Initialize Quill editor
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
        placeholder: 'Start writing...',
    });
    
    // Set initial content if value exists
    if (textareaElement.value) {
        quill.root.innerHTML = textareaElement.value;
    }
    
    // Update textarea on text change
    quill.on('text-change', function() {
        textareaElement.value = quill.root.innerHTML;
    });
    
    // Update textarea before form submission to ensure latest content is saved
    const form = textareaElement.closest('form');
    if (form) {
        form.addEventListener('submit', function() {
            textareaElement.value = quill.root.innerHTML;
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/components/tinymce-editor.blade.php ENDPATH**/ ?>