<?php $__env->startSection('title', $space->name); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="<?php echo e($space->icon ?? 'fas fa-folder'); ?> mr-2"></i><?php echo e($space->name); ?>

                </h1>
                <?php if($space->description): ?>
                    <p class="mt-2 text-gray-600"><?php echo e($space->description); ?></p>
                <?php endif; ?>
            </div>
            <div class="flex space-x-2">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $space)): ?>
                    <a href="<?php echo e(route('spaces.edit', $space->id)); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                        <i class="fas fa-edit mr-2"></i>Edit Space
                    </a>
                <?php endif; ?>
                <a href="<?php echo e(route('pages.create', ['space' => $space->id])); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    <i class="fas fa-plus mr-2"></i>Create Page
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Pages</h2>
        <?php if($pages->count() > 0): ?>
            <ul class="space-y-2">
                <?php $__currentLoopData = $pages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border-b pb-2">
                        <a href="<?php echo e(route('pages.show', $page->id)); ?>" class="text-blue-600 hover:text-blue-800 flex items-center">
                            <i class="fas fa-file-alt mr-2"></i><?php echo e($page->title); ?>

                            <span class="ml-auto text-sm text-gray-500">by <?php echo e($page->author->name); ?></span>
                        </a>
                        <?php if($page->children->count() > 0): ?>
                            <ul class="ml-6 mt-2 space-y-1">
                                <?php $__currentLoopData = $page->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <a href="<?php echo e(route('pages.show', $child->id)); ?>" class="text-blue-500 hover:text-blue-700 text-sm">
                                            <i class="fas fa-file mr-1"></i><?php echo e($child->title); ?>

                                        </a>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">No pages in this space yet. <a href="<?php echo e(route('pages.create', ['space' => $space->id])); ?>" class="text-blue-600">Create the first page</a></p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/spaces/show.blade.php ENDPATH**/ ?>