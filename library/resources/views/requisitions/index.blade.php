<x-layout>

    @if (session()->has('error'))
    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 4000)" x-show="show"
        class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 relative">
        <div class="flex">
            <div class="py-1">
                <svg class="h-6 w-6 text-red-500 mr-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div>
                <p class="font-bold">Error!</p>
                <p>{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="absolute top-0 right-0 mt-4 mr-4 text-red-500 hover:text-red-700">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
            </button>
        </div>
    </div>
    @endif

    <form action="{{ route('requisitions.create') }}" method="get">
        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center mb-3
               {{ $activeUserRequestsCount >= 3 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700' }}" {{
            $activeUserRequestsCount>= 3 ? 'disabled' : '' }}>
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $activeUserRequestsCount >= 3 ? 'Max requisitions reached' : 'New Requisition' }}
        </button>
    </form>

    <div class="overflow-x-auto">
        <table class="table-fixed w-full border-collapse border border-gray-300" style="table-layout: fixed;">
            <colgroup>
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
                <col style="width: 12%;">
            </colgroup>

            <thead>
                <tr>
                    <th class="border border-gray-300 px-4 py-2">#</th>
                    <th class="border border-gray-300 px-4 py-2">Book</th>
                    <th class="border border-gray-300 px-4 py-2">User Photo</th>
                    <th class="border border-gray-300 px-4 py-2">Citizen</th>
                    <th class="border border-gray-300 px-4 py-2">Start Date</th>
                    <th class="border border-gray-300 px-4 py-2">Expected Return</th>
                    <th class="border border-gray-300 px-4 py-2">Actual Return</th>
                    <th class="border border-gray-300 px-4 py-2">Status</th>
                    <th class="border border-gray-300 px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requisitions as $requisition)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->sequential_number }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->book->name ?? 'N/A' }}</td>

                    <td class="border border-gray-300 px-4 py-2 flex justify-center">
                        @if($requisition->citizen_photo)
                        <img src="{{ Storage::url($requisition->citizen_photo) }}" alt="Citizen Photo"
                            class="w-16 h-20 object-cover rounded">
                        @else
                        <span>-</span>
                        @endif
                    </td>

                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->user->name ?? 'N/A' }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->start_date }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->end_date }}</td>
                    <td class="border border-gray-300 px-4 py-2">{{ $requisition->actual_return_date ?? '-' }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <span
                            class="{{ $requisition->status === 'active' ? 'text-green-600 font-medium' : ($requisition->status === 'returned' ? 'text-red-600 font-medium' : 'text-gray-600') }} capitalize">
                            {{ $requisition->status }}
                        </span>
                    </td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        @can('view', $requisition)
                        <a href="{{ route('requisitions.show', $requisition) }}">Details</a>
                        @endcan
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('requisitions.edit', $requisition->id) }}"
                            class="ml-2 text-blue-500 hover:underline">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" class="w-5 h-5 inline mb-1">
                        </a>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center py-4">No requisitions found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-layout>