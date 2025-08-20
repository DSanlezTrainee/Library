<div>
    <div class="mb-4">
        <div class="flex gap-2 items-center">
            <input type="text" wire:model.live="search" placeholder="Search users..."
                class="max-w-md border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" />
        </div>
    </div>



    <div class="overflow-x-auto bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200">
        <table class="w-full table-fixed divide-y divide-gray-200 dark:divide-gray-700">
            <colgroup>
                <col style="width: 40%;">
                <col style="width: 40%;">
                <col style="width: 20%;">
            </colgroup>
            <thead class="bg-gray-900 text-white tracking-wide text-sm font-semibold text-center">
                <tr>
                    <th class="px-4 py-4 font-bold">Name</th>
                    <th class="px-4 py-4 font-bold">Email</th>
                    <th class="px-4 py-4 font-bold">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="hover:bg-gray-100 transition-colors duration-200 text-center">
                    <td class="px-4 py-2">{{ $user->name }}</td>
                    <td class="px-4 py-2">{{ $user->email }}</td>
                    <td class="px-4 py-2 text-center">
                        <a href="/users/{{ $user->id }}" class="text-blue-500 hover:underline">View Details</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" class="text-center py-4">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>