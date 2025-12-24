<?php $__env->startSection('title', 'Search'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-6">Search</h1>

    <form action="<?php echo e(route('search')); ?>" method="GET" class="mb-6">
        <div class="flex">
            <input type="text" name="q" value="<?php echo e($query); ?>" 
                   placeholder="Search pages, spaces, and comments..."
                   class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-r-md hover:bg-blue-700">
                <i class="fas fa-search"></i> Search
            </button>
        </div>
    </form>

    <?php if($query): ?>
        <div class="mb-4">
            <p class="text-gray-600">Found <?php echo e($results->count()); ?> result(s) for "<strong><?php echo e($query); ?></strong>"</p>
        </div>

        <?php if($results->count() > 0): ?>
            <div class="space-y-4">
                <?php $__currentLoopData = $results; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="bg-white shadow rounded-lg p-6 hover:shadow-lg transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded mr-2 uppercase">
                                        <?php echo e($result['type']); ?>

                                    </span>
                                    <a href="<?php echo e($result['url']); ?>" class="text-xl font-semibold text-blue-600 hover:text-blue-800">
                                        <?php echo e($result['title']); ?>

                                    </a>
                                </div>
                                <p class="text-gray-600 mb-2"><?php echo e($result['description']); ?></p>
                                <div class="text-sm text-gray-500">
                                    <?php if(isset($result['space'])): ?>
                                        <span><i class="fas fa-folder mr-1"></i><?php echo e($result['space']); ?></span>
                                    <?php endif; ?>
                                    <span class="ml-4"><i class="fas fa-user mr-1"></i><?php echo e($result['author']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php else: ?>
            <div class="bg-white shadow rounded-lg p-12 text-center">
                <p class="text-gray-500 text-lg">No results found.</p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white shadow rounded-lg p-12 text-center">
            <p class="text-gray-500 text-lg">Enter a search query to get started.</p>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/search/index.blade.php ENDPATH**/ ?>