<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('roles.update', $role) }}">
                        @csrf
                        @method('PUT')

                        <!-- Role Name -->
                        <div>
                            <x-input-label for="name" :value="__('Role Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $role->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Permissions -->
                        <div class="mt-4">
                            <x-input-label for="permissions" :value="__('Permissions')" />
                            @php
                                $groupedPermissions = $permissions->groupBy(function ($permission) {
                                    $parts = explode('-', $permission->name, 2);
                                    return ucfirst($parts[0]);
                                });
                            @endphp

                            <div class="mt-2 space-y-4">
                                @foreach($groupedPermissions as $module => $modulePermissions)
                                    <div class="border p-4 rounded-md">
                                        <h4 class="font-semibold text-gray-800 mb-2">{{ $module }} Management</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            @foreach($modulePermissions as $permission)
                                                <label for="permission_{{ $permission->id }}" class="inline-flex items-center">
                                                    <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" value="{{ $permission->name }}" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                                                    <span class="ms-2 text-sm text-gray-600">{{ $permission->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ms-4">
                                {{ __('Update Role') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>