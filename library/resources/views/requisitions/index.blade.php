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

    <!-- Indicadores na parte superior -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <h5 class="text-gray-500 font-medium">Active Requests</h5>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $activeRequisitionsCount }}</h2>
                </div>
                <div class="flex items-center justify-center  h-12 w-12 rounded-full">
                    <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <h5 class="text-gray-500 font-medium">Last 30 Days</h5>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $last30DaysRequisitionsCount }}</h2>
                </div>
                <div class="flex items-center justify-center  h-12 w-12 rounded-full">
                    <svg class="h-6 w-6 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-5">
            <div class="flex justify-between">
                <div>
                    <h5 class="text-gray-500 font-medium">Returned Today</h5>
                    <h2 class="text-3xl font-bold text-gray-800">{{ $returnedTodayCount }}</h2>
                </div>
                <div class="flex items-center justify-center  h-12 w-12 rounded-full">
                    <svg class="h-6 w-6 text-purple-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
        </div>
    </div>


    <form action="{{ route('requisitions.create') }}" method="get">
        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded inline-flex items-center mb-3
               {{ $activeUserRequestsCount >= 3 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-blue-700' }}" {{
            $activeUserRequestsCount>= 3 ? 'disabled' : '' }}>
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $activeUserRequestsCount >= 3 ? 'Max requests reached' : 'New Request' }}
        </button>
    </form>

    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
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

            <thead class="bg-gray-900 text-white tracking-wide text-sm font-semibold">
                <tr>
                    <th class="px-4 py-4 min-h-[2rem]">#</th>
                    <th class="px-4 py-4 min-h-[2rem]">Book</th>
                    <th class="px-4 py-4 min-h-[2rem]">User Photo</th>
                    <th class="px-4 py-4 min-h-[2rem]">Citizen</th>
                    <th class="px-4 py-4 min-h-[2rem]">Start Date</th>
                    <th class="px-4 py-4 min-h-[2rem]">Expected Return</th>
                    <th class="px-4 py-4 min-h-[2rem]">Actual Return</th>
                    <th class="px-4 py-4 min-h-[2rem]">Status</th>
                    <th class="px-4 py-4 min-h-[2rem]">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($requisitions as $requisition)
                <tr class="hover:bg-gray-100 transition-colors duration-200 text-center">
                    <td class="px-4 py-2 text-center">{{ $requisition->sequential_number }}</td>
                    <td class="px-4 py-2">{{ $requisition->book->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2 flex justify-center">
                        @if($requisition->citizen_photo)
                        <img src="{{ Storage::url($requisition->citizen_photo) }}" alt="Citizen Photo"
                            class="w-16 h-20 object-cover rounded">
                        @else

                        <span>-</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">{{ $requisition->user->name ?? 'N/A' }}</td>
                    <td class="px-4 py-2">{{ $requisition->start_date }}</td>
                    <td class="px-4 py-2">{{ $requisition->end_date }}</td>
                    <td class="px-4 py-2">{{ $requisition->actual_return_date ?? '-' }}</td>
                    <td class="px-4 py-2 text-center">
                        <span
                            class="{{ $requisition->status === 'active' ? 'text-green-600 font-medium' : ($requisition->status === 'returned' ? 'text-red-600 font-medium' : 'text-gray-600') }} capitalize">
                            {{ $requisition->status }}
                        </span>
                    </td>
                    <td class="px-4 py-2 text-center space-x-2">
                        @can('view', $requisition)
                        <a href="{{ route('requisitions.show', $requisition) }}"
                            class="text-blue-600 hover:underline">Details</a>
                        @endcan
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('requisitions.edit', $requisition->id) }}"
                            class="text-yellow-600 hover:underline">
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


    <div class="mt-4">
        {{ $requisitions->links() }}
    </div>
</x-layout>