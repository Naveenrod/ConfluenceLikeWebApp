<?php $__env->startSection('title', 'Spaces'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Spaces</h1>
        <a href="<?php echo e(route('spaces.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i>Create Space
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php $__empty_1 = true; $__currentLoopData = $spaces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $space): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-semibold mb-2">
                            <a href="<?php echo e(route('spaces.show', $space->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-folder mr-2"></i><?php echo e($space->name); ?>

                            </a>
                        </h3>
                        <p class="text-gray-600 text-sm mb-4"><?php echo e(Str::limit($space->description, 100)); ?></p>
                        <div class="flex items-center text-sm text-gray-500">
                            <span><i class="fas fa-user mr-1"></i><?php echo e($space->owner->name); ?></span>
                            <span class="ml-4"><i class="fas fa-file-alt mr-1"></i><?php echo e($space->pages->count()); ?> pages</span>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex space-x-2">
                    <a href="<?php echo e(route('spaces.show', $space->id)); ?>" class="text-blue-600 hover:text-blue-800 text-sm">
                        View <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('update', $space)): ?>
                        <a href="<?php echo e(route('spaces.edit', $space->id)); ?>" class="text-gray-600 hover:text-gray-800 text-sm">
                            Edit
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 text-center py-12">
                <p class="text-gray-500 mb-4">No spaces yet.</p>
                <a href="<?php echo e(route('spaces.create')); ?>" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Create Your First Space
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if($spaces->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($spaces->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/spaces/index.blade.php ENDPATH**/ ?>