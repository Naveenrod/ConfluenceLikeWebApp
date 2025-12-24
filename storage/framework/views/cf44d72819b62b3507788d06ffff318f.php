<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="mt-2 text-sm text-gray-600">Welcome back, <?php echo e(Auth::user()->name); ?>!</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Spaces</h2>
            <?php if($recentSpaces->count() > 0): ?>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $recentSpaces; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $space): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('spaces.show', $space->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-folder mr-2"></i><?php echo e($space->name); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No spaces yet. <a href="<?php echo e(route('spaces.create')); ?>" class="text-blue-600">Create one</a></p>
            <?php endif; ?>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Recent Pages</h2>
            <?php if($recentPages->count() > 0): ?>
                <ul class="space-y-2">
                    <?php $__currentLoopData = $recentPages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a href="<?php echo e(route('pages.show', $page->id)); ?>" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-file-alt mr-2"></i><?php echo e($page->title); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php else: ?>
                <p class="text-gray-500">No pages yet.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/dashboard.blade.php ENDPATH**/ ?>