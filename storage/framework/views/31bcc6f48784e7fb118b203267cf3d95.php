<?php $__env->startSection('title', $user->name . ' - Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="h-20 w-20 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>
                <div class="ml-6">
                    <h1 class="text-3xl font-bold text-gray-900"><?php echo e($user->name); ?></h1>
                    <p class="text-gray-600"><?php echo e($user->email); ?></p>
                    <?php if($user->bio): ?>
                        <p class="mt-2 text-gray-700"><?php echo e($user->bio); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php if(Auth::id() === $user->id): ?>
                <a href="<?php echo e(route('profile.edit')); ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    <i class="fas fa-edit mr-2"></i>Edit Profile
                </a>
            <?php endif; ?>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6 mb-6">
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600"><?php echo e($stats['pages']); ?></div>
            <div class="text-gray-600 mt-2">Pages</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600"><?php echo e($stats['spaces']); ?></div>
            <div class="text-gray-600 mt-2">Spaces</div>
        </div>
        <div class="bg-white shadow rounded-lg p-6 text-center">
            <div class="text-3xl font-bold text-blue-600"><?php echo e($stats['comments']); ?></div>
            <div class="text-gray-600 mt-2">Comments</div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Recent Activity</h2>
        <?php if($activities->count() > 0): ?>
            <ul class="space-y-4">
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="border-b pb-4 last:border-0">
                        <div class="flex items-start">
                            <div class="flex-1">
                                <p class="text-gray-900"><?php echo e($activity->description); ?></p>
                                <p class="text-sm text-gray-500 mt-1"><?php echo e($activity->created_at->diffForHumans()); ?></p>
                            </div>
                        </div>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        <?php else: ?>
            <p class="text-gray-500">No activity yet.</p>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/profile/show.blade.php ENDPATH**/ ?>