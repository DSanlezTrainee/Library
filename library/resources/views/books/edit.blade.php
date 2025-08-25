<x-layout>
    <x-slot name="heading">
        Edit Book
    </x-slot>

    <div class="flex justify-center items-center min-h-screen py-8">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-xl">
            <div class="bg-gray-900 text-white text-center py-3 px-6 rounded-t-lg">
                <h3 class="text-xl font-bold">{{ $book->name }}</h3>
            </div>
            <div class="px-6 py-3">
                <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Book Title</label>
                        <input type="text"
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror"
                            id="name" name="name" required value="{{ old('name', $book->name) }}"
                            placeholder="Enter book title" required>
                        @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Cover Image</label>
                        @if($book->cover_image)
                        <div class="mb-2">
                            <p class="text-sm text-gray-600">Current image: {{ basename($book->cover_image) }}</p>
                        </div>
                        @endif
                        <input type="file"
                            class="w-full px-3 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('image') border-red-500 @enderror"
                            id="image" name="image" accept="image/*">
                        @error('image')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">You can upload a new cover image (optional)</p>
                    </div>

                    <div class="mb-4">
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                        <input type="number"
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('isbn') border-red-500 @enderror"
                            id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                            placeholder="Enter ISBN (13 digits)" required pattern="[0-9]{13}" inputmode="numeric"
                            min="0" maxlength="13"
                            oninput="javascript: if (this.value.length > 13) this.value = this.value.slice(0, 13);">
                        @error('isbn')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                        <div class="flex rounded-full overflow-hidden">
                            <span class="bg-gray-200 px-3 py-2 flex items-center">€</span>
                            <input type="number" step="0.01"
                                class="w-full px-4 py-2 border border-gray-300 border-l-0 focus:outline-none focus:ring-2 focus:ring-blue-500 @error('price') border-red-500 @enderror"
                                id="price" name="price" value="{{ old('price', $book->price) }}"
                                placeholder="Enter price" required>
                        </div>
                        @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="author_ids" class="block text-sm font-medium text-gray-700 mb-1">Authors</label>
                        <select
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('author_ids') border-red-500 @enderror"
                            id="author_ids" name="author_ids[]" multiple required style="height: 120px;">
                            @foreach($authors as $author)
                            <option value="{{ $author->id }}" {{ (collect(old('author_ids', $book->
                                authors->pluck('id')))->contains($author->id)) ? 'selected' : '' }}>
                                {{ $author->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('author_ids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-gray-500 text-xs mt-1">Hold Ctrl (Cmd on Mac) to select multiple authors</p>
                    </div>

                    <div class="mb-4">
                        <label for="publisher_id" class="block text-sm font-medium text-gray-700 mb-1">Publisher</label>
                        <select
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('publisher_id') border-red-500 @enderror"
                            id="publisher_id" name="publisher_id" required>
                            <option value="">Select a publisher</option>
                            @foreach($publishers as $publisher)
                            <option value="{{ $publisher->id }}" {{ old('publisher_id', $book->publisher_id) ==
                                $publisher->id ? 'selected' : '' }}>
                                {{ $publisher->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('publisher_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="bibliography"
                            class="block text-sm font-medium text-gray-700 mb-1">Bibliography</label>
                        <textarea
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('bibliography') border-red-500 @enderror"
                            id="bibliography" name="bibliography" rows="4"
                            placeholder="Enter bibliography details">{{ old('bibliography', $book->bibliography) }}</textarea>
                        @error('bibliography')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('books.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 border border-gray-300 text-black rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Update Book
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>