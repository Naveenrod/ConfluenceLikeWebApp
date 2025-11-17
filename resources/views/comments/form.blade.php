<form action="{{ route('comments.store', $page->id) }}" method="POST" class="mb-6">
    @csrf
    <div class="mb-4">
        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Add a comment</label>
        <textarea name="content" id="content" rows="3" required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                  placeholder="Write your comment here..."></textarea>
    </div>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Post Comment
    </button>
</form>

