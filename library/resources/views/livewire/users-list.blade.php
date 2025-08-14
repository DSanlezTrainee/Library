<div>
    <div class="mb-4">
        <div class="flex gap-2 items-center">
            <input type="text" wire:model.live="search" placeholder="Search users..." class="input input-primary"
                style="width: 300px;" />
        </div>
    </div>



    <div class="overflow-x-auto">
        <table class="table-fixed w-full border-collapse border border-gray-300" style="table-layout: fixed;">

            <colgroup>
                <col style="width: 15%;">
                <col style="width: 15%;">
                <col style="width: 10%;">
            </colgroup>

            <thead>
                <tr class="bg-gray-50">
                    <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
                    <th class="border border-gray-300 px-4 py-2 text-left">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                <tr class="hover:bg-gray-50 transition-colors duration-200">
                    <td class="border border-gray-300 px-4 py-2">
                        <div class="flex items-center">
                            {{ $user->name }}
                        </div>
                    </td>
                    <td class="border border-gray-300 px-4 py-2">{{ $user->email }}</td>
                    <td class="border border-gray-300 px-4 py-2 text-center">
                        <a href="/users/{{ $user->id }}" class="text-blue-500 text-align:center hover:underline">View
                            Details</a>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4">No users found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>
</div>