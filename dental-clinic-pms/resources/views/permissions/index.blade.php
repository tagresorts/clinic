<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Permissions') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            {{ __('Create Permission') }}
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        @foreach ($permissions as $group => $groupPermissions)
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $group }}</h3>
                                <div class="bg-white shadow rounded-lg overflow-hidden">
                                    <ul class="divide-y divide-gray-200">
                                        @foreach ($groupPermissions as $permission)
                                            <li class="px-6 py-4 flex items-center justify-between">
                                                <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                                <div class="flex items-center">
                                                    <a href="{{ route('permissions.edit', $permission) }}" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</a>
                                                    <form action="{{ route('permissions.destroy', $permission) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this permission?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
