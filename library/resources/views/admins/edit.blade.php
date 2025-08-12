<x-layout>
    <x-slot name="heading">
        Edit Admin
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <h1 class="text-2xl font-bold mb-6">Edit Admin: {{ $admin->name }}</h1>

                <x-validation-errors class="mb-4" />

                <form method="POST" action="{{ route('admins.update', $admin) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                        <input id="name" type="text" name="name" value="{{ old('name', $admin->name) }}" required
                            autofocus
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="email" type="email" name="email" value="{{ old('email', $admin->email) }}" required
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password (leave
                            blank to keep current password)</label>
                        <input id="password" type="password" name="password"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm
                            New Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <a href="{{ route('admins.index') }}"
                            class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 transition mr-2">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Update Admin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layout>