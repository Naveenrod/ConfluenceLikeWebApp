<div class="border-l-2 border-gray-200 pl-4 mb-4">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <div class="flex items-center mb-2">
                <strong class="text-gray-900">{{ $comment->user->name }}</strong>
                <span class="ml-2 text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <div class="text-gray-700 whitespace-pre-wrap">{{ $comment->content }}</div>
            
            @if(Auth::id() === $comment->user_id)
                <div class="mt-2 flex space-x-2">
                    <form action="{{ route('comments.destroy', $comment->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm" 
                                onclick="return confirm('Are you sure you want to delete this comment?')">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <!-- Reply form -->
    <div class="mt-4 ml-4">
        <form action="{{ route('comments.store', $comment->page_id) }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <textarea name="content" rows="2" required
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-sm"
                      placeholder="Reply to this comment..."></textarea>
            <button type="submit" class="mt-2 bg-gray-600 text-white px-3 py-1 rounded-md hover:bg-gray-700 text-sm">
                Reply
            </button>
        </form>
    </div>

    <!-- Replies -->
    @if($comment->replies->count() > 0)
        <div class="mt-4 ml-4 space-y-4">
            @foreach($comment->replies as $reply)
                @include('comments.item', ['comment' => $reply])
            @endforeach
        </div>
    @endif
</div>

