<x-layout>
    <x-slot name="heading">
        Administrators
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-xl text-gray-800">
            Admin Users
        </h2>
        <a href="{{ route('admins.create') }}"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
            Create New Admin
        </a>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="overflow-x-auto">
                    <table class="table-fixed w-full border-collapse border border-gray-300">
                        <thead>
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Name</th>
                                <th class="border border-gray-300 px-4 py-2">Email</th>
                                <th class="border border-gray-300 px-4 py-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($admins as $admin)
                            <tr class="hover:bg-gray-50 transition-colors duration-200">
                                <td class="border border-gray-300 px-4 py-2">{{ $admin->name }}</td>
                                <td class="border border-gray-300 px-4 py-2">{{ $admin->email }}</td>
                                <td class="border border-gray-300 px-4 py-2 text-center">
                                    <a href="{{ route('admins.edit', $admin) }}" class="text-blue-500 hover:underline">
                                        <img src="{{ asset('images/edit.png') }}" alt="Edit"
                                            class="w-5 h-5 inline mb-2">
                                    </a>
                                    <form action="{{ route('admins.destroy', $admin) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:underline"
                                            onclick="return confirm('Are you sure you want to delete this admin?')">
                                            <img src="{{ asset('images/remove.png') }}" alt="Delete"
                                                class="w-5 h-5 inline">
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">No admins found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-layout>