<?php $__env->startSection('title', $page->title); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Breadcrumbs -->
    <nav class="mb-4 text-sm">
        <ol class="flex items-center space-x-2">
            <li><a href="<?php echo e(route('dashboard')); ?>" class="text-blue-600 hover:text-blue-800">Dashboard</a></li>
            <li><span class="text-gray-500">/</span></li>
            <li><a href="<?php echo e(route('spaces.show', $page->space->id)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($page->space->name); ?></a></li>
            <?php $__currentLoopData = $breadcrumbs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><span class="text-gray-500">/</span></li>
                <li>
                    <?php if($crumb->id === $page->id): ?>
                        <span class="text-gray-900"><?php echo e($crumb->title); ?></span>
                    <?php else: ?>
                        <a href="<?php echo e(route('pages.show', $crumb->id)); ?>" class="text-blue-600 hover:text-blue-800"><?php echo e($crumb->title); ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ol>
    </nav>

    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-start mb-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2"><?php echo e($page->title); ?></h1>
                <div class="flex items-center text-sm text-gray-500 space-x-4">
                    <span><i class="fas fa-user mr-1"></i><?php echo e($page->author->name); ?></span>
                    <span><i class="fas fa-clock mr-1"></i><?php echo e($page->updated_at->diffForHumans()); ?></span>
                    <span><i class="fas fa-folder mr-1"></i><?php echo e($page->space->name); ?></span>
                </div>
            </div>
            <div class="flex space-x-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $page)): ?>
                    <a href="<?php echo e(route('pages.edit', $page->id)); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                        <i class="fas fa-edit mr-2"></i>Edit
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('pages.versions', $page->id)); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 text-sm">
                    <i class="fas fa-history mr-2"></i>History
                </a>
            </div>
        </div>

        <div class="prose max-w-none">
            <?php echo $page->content; ?>

        </div>
    </div>

    <!-- Sub-pages -->
    <?php if($page->children->count() > 0): ?>
        <div class="bg-white shadow rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Child Pages</h2>
            <ul class="space-y-2">
                <?php $__currentLoopData = $page->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li>
                        <a href="<?php echo e(route('pages.show', $child->id)); ?>" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-file-alt mr-2"></i><?php echo e($child->title); ?>

                        </a>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Attachments -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Attachments</h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $page)): ?>
                <form action="<?php echo e(route('attachments.store', $page->id)); ?>" method="POST" enctype="multipart/form-data" class="flex items-center">
                    <?php echo csrf_field(); ?>
                    <input type="file" name="file" required class="mr-2">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 text-sm">
                        <i class="fas fa-upload mr-2"></i>Upload
                    </button>
                </form>
            <?php endif; ?>
        </div>
        <?php if($page->attachments->count() > 0): ?>
            <ul class="space-y-2">
                <?php $__currentLoopData = $page->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attachment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center justify-between border-b pb-2">
                        <div class="flex items-center">
                            <a href="<?php echo e(route('attachments.download', $attachment->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file mr-2"></i><?php echo e($attachment->original_filename); ?>

                            </a>
                            <span class="ml-4 text-sm text-gray-500"><?php echo e($attachment->human_readable_size); ?></span>
                        </div>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $page)): ?>
                            <form action="<?php echo e(route('attachments.destroy', $attachment->id)); ?>" method="POST" class="inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm"
                                        onclick="return confirm('Are you sure you want to delete this file?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">No attachments yet.</p>
        <?php endif; ?>
    </div>

    <!-- Comments -->
    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Comments</h2>
        
        <?php echo $__env->make('comments.form', ['page' => $page], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div class="mt-6 space-y-4">
            <?php $__currentLoopData = $page->comments->where('parent_id', null); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('comments.item', ['comment' => $comment], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/pages/show.blade.php ENDPATH**/ ?>