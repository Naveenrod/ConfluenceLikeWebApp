<div class="border-l-2 border-gray-200 pl-4 mb-4">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-2">
                <strong class="text-gray-900"><?php echo e($comment->user->name); ?></strong>
                <span class="ml-2 text-sm text-gray-500"><?php echo e($comment->created_at->diffForHumans()); ?></span>
            </div>
            <div class="text-gray-700 whitespace-pre-wrap"><?php echo e($comment->content); ?></div>
            
            <?php if(Auth::id() === $comment->user_id): ?>
                <div class="mt-2 flex space-x-2">
                    <form action="<?php echo e(route('comments.destroy', $comment->id)); ?>" method="POST" class="inline">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                onclick="return confirm('Are you sure you want to delete this comment?')">
                            Delete
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Reply form -->
    <div class="mt-4 ml-4">
        <form action="<?php echo e(route('comments.store', $comment->page_id)); ?>" method="POST">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="parent_id" value="<?php echo e($comment->id); ?>">
            <textarea name="content" rows="2" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      placeholder="Reply to this comment..."></textarea>
            <button type="submit" class="mt-2 bg-gray-600 text-white px-3 py-1 rounded-md hover:bg-gray-700 text-sm">
                Reply
            </button>
        </form>
    </div>

    <!-- Replies -->
    <?php if($comment->replies->count() > 0): ?>
        <div class="mt-4 ml-4 space-y-4">
            <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('comments.item', ['comment' => $reply], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<?php /**PATH /Users/naveenrodrigo/Documents/ConfluenceLikeWebApp/resources/views/comments/item.blade.php ENDPATH**/ ?>