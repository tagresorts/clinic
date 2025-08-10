<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit SMTP Configuration') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('smtp.update', $smtp) }}">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Name</label>
                                <input name="name" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->name }}" required />
                            </div>
                            <div>
                                <label class="block text-sm">Host</label>
                                <input name="host" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->host }}" required />
                            </div>
                            <div>
                                <label class="block text-sm">Port</label>
                                <input type="number" name="port" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->port }}" />
                            </div>
                            <div>
                                <label class="block text-sm">Encryption</label>
                                <select name="encryption" class="mt-1 w-full border-gray-300 rounded">
                                    <option value="" @selected(!$smtp->encryption)>None</option>
                                    <option value="tls" @selected($smtp->encryption==='tls')>TLS</option>
                                    <option value="ssl" @selected($smtp->encryption==='ssl')>SSL</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm">Username</label>
                                <input name="username" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->username }}" />
                            </div>
                            <div>
                                <label class="block text-sm">Password (leave blank to keep unchanged)</label>
                                <input type="password" name="password" class="mt-1 w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm">From Email</label>
                                <input type="email" name="from_email" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->from_email }}" />
                            </div>
                            <div>
                                <label class="block text-sm">From Name</label>
                                <input name="from_name" class="mt-1 w-full border-gray-300 rounded" value="{{ $smtp->from_name }}" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" class="mr-2" @checked($smtp->is_active)> Active</label>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-between">
                            <form action="{{ route('smtp.set-default', $smtp) }}" method="POST">@csrf <x-secondary-button>Set Default</x-secondary-button></form>
                            <x-primary-button>Save</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
