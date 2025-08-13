<x-layout>
    <x-slot:heading>
        Book Details
    </x-slot:heading>

    <h2 class="text-lg font-bold">{{ $book->name }}</h2>

    <div class="mt-4">
        <strong>Bibliography:</strong> {{ $book->bibliography ?? 'No Bibliography' }}
    </div>

    <div class="mt-6">
        <h3 class="text-md font-semibold mb-2">Requisition History</h3>

        @if($book->requisitions && $book->requisitions->isNotEmpty())
        <table class="w-full table-auto border border-gray-200">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2">User</th>
                    <th class="border px-4 py-2">Requested At</th>
                    <th class="border px-4 py-2">Returned At</th>
                    <th class="border px-4 py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($book->requisitions as $requisition)
                <tr>
                    <td class="border px-4 py-2">{{ $requisition->user->name ?? 'N/A' }}</td>
                    <td class="border px-4 py-2">{{ $requisition->created_at->format('d/m/Y') }}</td>
                    <td class="border px-4 py-2">{{ $requisition->actual_return_date ?
                        \Carbon\Carbon::parse($requisition->actual_return_date)->format('d/m/Y') : '-' }}</td>
                    <td class="border px-4 py-2">
                        @if($requisition->actual_return_date)
                        <span class="text-gray-500">Returned</span>
                        @else
                        <span class="text-green-600 font-bold">Active</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No requisitions found for this book.</p>
        @endif
    </div>
</x-layout>