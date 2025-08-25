<!-- filepath: d:\Users\Diogo\Desktop\TrabalhoEstagio\library\resources\views\users\show.blade.php -->
<x-layout>
    <x-slot name="heading">
        User Details
    </x-slot>

    <div class="bg-white shadow-md rounded-lg p-6 mb-6">
        <div class="flex items-center mb-4">
            <div class="mr-4">
                <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : asset('images/default-avatar.png') }}"
                    alt="{{ $user->name }}" class="w-20 h-20 rounded-full object-cover">
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h2>
                <p class="text-gray-600">{{ $user->email }}</p>
                <p class="text-gray-600 capitalize">Role: <span class="font-semibold">{{ $user->role }}</span></p>
            </div>
        </div>
    </div>

    <div class="mt-6">
        <h3 class="text-md font-semibold mb-2">Requests History</h3>

        @if($user->requisitions->isNotEmpty())
        <div class="overflow-x-auto bg-white dark:bg-gray-900 rounded-lg shadow border border-gray-200">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-900 text-white  tracking-wide text-sm font-semibold">
                    <tr>
                        <th class="px-4 py-4 min-h-[2rem]">Requested At</th>
                        <th class="px-4 py-4 min-h-[2rem]">Returned At</th>
                        <th class="px-4 py-4 min-h-[2rem]">Status</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($user->requisitions as $requisition)
                    <tr>
                        <td class="px-4 py-2">{{ $requisition->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-2">{{ $requisition->actual_return_date ?
                            \Carbon\Carbon::parse($requisition->actual_return_date)->format('d/m/Y') : '-' }}</td>
                        <td class="px-4 py-2 text-center">
                            @if($requisition->actual_return_date)
                            <span class="bg-red-100 text-red-600">Returned</span>
                            @else
                            <span class="bg-green-100 text-green-600 font-bold">Active</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <p>No requests found.</p>
        @endif
    </div>

</x-layout>