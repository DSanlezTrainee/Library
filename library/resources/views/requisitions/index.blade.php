<x-layout>
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
                    <th class="border border-gray-300 px-4 py-2">Citizen Photo</th>
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
                    <td class="border border-gray-300 px-4 py-2 capitalize">{{ $requisition->status }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <a href="{{ route('requisitions.show', $requisition->id) }}"
                            class="text-blue-500 hover:underline">View</a>
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