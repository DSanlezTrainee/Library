<x-layout>
    <x-slot name="heading">
        New Request
    </x-slot>

    <div class="flex justify-center items-center  py-3">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-xl">
            <div class=" text-black text-center py-3 px-6 rounded-t-lg">
                <h3 class="text-xl font-bold">Create Request</h3>
            </div>
            <div class="px-6 py-3">
                <form method="POST" action="{{ route('requisitions.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- Book select --}}
                    <div class="mb-4">
                        <label for="book_id" class="block text-sm font-medium text-gray-700 mb-1">Book</label>
                        <select id="book_id" name="book_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('book_id') border-red-500 @enderror"
                            required>
                            <option value="">Select a book</option>
                            @foreach($booksAvailable as $book)
                            <option value="{{ $book->id }}" {{ old('book_id', $selectedBookId ?? '') == $book->id ? 'selected' : '' }}>
                                {{ $book->name }} — {{ $book->authors->pluck('name')->join(', ') }}
                            </option>
                            @endforeach
                        </select>
                        @error('book_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- If admin can create for other users, show a user select; otherwise hidden user is used in
                    controller --}}
                    @if(auth()->user()->isAdmin())
                    <div class="mb-4">
                        <label for="user_id" class="block text-sm font-medium text-gray-700 mb-1">Citizen</label>
                        <select id="user_id" name="user_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('user_id') border-red-500 @enderror"
                            required>
                            <option value="">Select a user</option>
                            @foreach($users as $u)
                            <option value="{{ $u->id }}" {{ old('user_id')==$u->id ? 'selected' : '' }}>
                                {{ $u->name }} ({{ $u->email }})
                            </option>
                            @endforeach
                        </select>
                        @error('user_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif

                    {{-- Citizen photo (obrigatória) --}}
                    <div class="mb-4">
                        <label for="citizen_photo" class="block text-sm font-medium text-gray-700 mb-1">Citizen
                            Photo</label>
                        <input type="file" id="citizen_photo" name="citizen_photo" accept="image/*"
                            class="w-full px-3 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('citizen_photo') border-red-500 @enderror"
                            required>
                        @error('citizen_photo') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        <p class="text-gray-500 text-xs mt-1">Photo taken at the moment of request (jpg, png). Max 2MB.
                        </p>
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('requisitions.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 border border-gray-300 text-black rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Create Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>