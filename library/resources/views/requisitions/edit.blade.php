<x-layout>
    <x-slot name="heading">
        Edit Requisition
    </x-slot>

    @if (session()->has('success'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 relative">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-green-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <div>
                <p class="font-bold">Success!</p>
                <p>{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-green-500 hover:text-green-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <div class="flex justify-center items-center py-3">
        <div class="bg-white shadow-lg rounded-lg w-full max-w-xl">
            <div class="text-black text-center py-3 px-6 rounded-t-lg">
                <h3 class="text-xl font-bold">Confirm Book Return</h3>
            </div>
            <div class="px-6 py-3">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div class="border border-gray-200 rounded p-4">
                        <h4 class="font-semibold text-lg mb-2">Requisition Details</h4>
                        <p><span class="font-medium">Book:</span> {{ $requisition->book->name }}</p>
                        <p><span class="font-medium">Citizen:</span> {{ $requisition->user->name }}</p>
                        <p><span class="font-medium">Start Date:</span> {{
                            \Carbon\Carbon::parse($requisition->start_date)->format('d/m/Y') }}</p>
                        <p><span class="font-medium">Expected Return:</span> {{
                            \Carbon\Carbon::parse($requisition->end_date)->format('d/m/Y') }}</p>
                        <p><span class="font-medium">Status:</span>
                            <span
                                class="inline-block py-1 px-2 rounded {{ $requisition->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($requisition->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="border border-gray-200 rounded p-4 flex justify-center items-center">
                        @if($requisition->citizen_photo)
                        <img src="{{ Storage::url($requisition->citizen_photo) }}" alt="Citizen Photo"
                            class="max-h-40 object-cover rounded">
                        @else
                        <div class="text-gray-500">No photo available</div>
                        @endif
                    </div>
                </div>

                <form method="POST" action="{{ route('requisitions.update', $requisition) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="actual_return_date" class="block text-sm font-medium text-gray-700 mb-1">Actual
                            Return Date</label>
                        <input type="date" id="actual_return_date" name="actual_return_date"
                            value="{{ old('actual_return_date', $requisition->actual_return_date ? \Carbon\Carbon::parse($requisition->actual_return_date)->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d')) }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 @error('actual_return_date') border-red-500 @enderror">
                        @error('actual_return_date') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <div class="flex items-center">
                            <input type="checkbox" id="admin_confirmed" name="admin_confirmed" value="1" {{
                                old('admin_confirmed', $requisition->admin_confirmed) ? 'checked' : '' }}
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="admin_confirmed" class="ml-2 block text-sm font-medium text-gray-700">
                                Confirm book has been returned in good condition
                            </label>
                        </div>
                        @error('admin_confirmed') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h4 class="font-medium text-gray-700 mb-2">Return Summary</h4>
                        @if($requisition->actual_return_date)
                        @php
                        $returnDate = \Carbon\Carbon::parse($requisition->actual_return_date);
                        $expectedDate = \Carbon\Carbon::parse($requisition->end_date);
                        $daysLate = $returnDate->isAfter($expectedDate) ? $returnDate->diffInDays($expectedDate) : 0;
                        @endphp

                        <p>
                            <span class="font-medium">Days late:</span>
                            <span class="{{ $daysLate > 0 ? 'text-red-600 font-bold' : 'text-green-600' }}">
                                {{ $daysLate }} {{ $daysLate === 1 ? 'day' : 'days' }}
                                {{ $daysLate > 0 ? '(late)' : '(on time)' }}
                            </span>
                        </p>
                        @else
                        <p class="text-gray-500 italic">Return date not yet confirmed.</p>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-4 mt-6">
                        <a href="{{ route('requisitions.index') }}"
                            class="px-6 py-2 border border-gray-300 rounded-full text-gray-700 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 border border-gray-300 text-black rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Confirm Return
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>