<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add SMTP Configuration') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('smtp.store') }}">
                        @csrf
                        <div class="mb-4 flex items-center gap-2">
                            <span class="text-sm text-gray-600">Quick presets:</span>
                            <button type="button" id="preset-gmail" class="px-3 py-1.5 bg-gray-100 rounded text-sm">Gmail</button>
                            <button type="button" id="preset-m365" class="px-3 py-1.5 bg-gray-100 rounded text-sm">Microsoft 365</button>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm">Name</label>
                                <input name="name" class="mt-1 w-full border-gray-300 rounded" required />
                            </div>
                            <div>
                                <label class="block text-sm">Host</label>
                                <input name="host" class="mt-1 w-full border-gray-300 rounded" required />
                            </div>
                            <div>
                                <label class="block text-sm">Port</label>
                                <input type="number" name="port" class="mt-1 w-full border-gray-300 rounded" value="587" />
                            </div>
                            <div>
                                <label class="block text-sm">Encryption</label>
                                <select name="encryption" class="mt-1 w-full border-gray-300 rounded">
                                    <option value="">None</option>
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm">Username</label>
                                <input name="username" class="mt-1 w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm">Password</label>
                                <input type="password" name="password" class="mt-1 w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm">From Email</label>
                                <input type="email" name="from_email" class="mt-1 w-full border-gray-300 rounded" />
                            </div>
                            <div>
                                <label class="block text-sm">From Name</label>
                                <input name="from_name" class="mt-1 w-full border-gray-300 rounded" />
                            </div>
                            <div class="md:col-span-2">
                                <label class="inline-flex items-center"><input type="checkbox" name="is_active" value="1" class="mr-2" checked> Active</label>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <x-primary-button>Save</x-primary-button>
                        </div>
                    </form>
                    @push('scripts')
                    <script>
                    (function(){
                      const qs = s => document.querySelector(s);
                      const setVal = (name, val) => { const el = qs(`[name="${name}"]`); if (el) el.value = val; };
                      const setSel = (name, val) => { const el = qs(`[name="${name}"]`); if (el) el.value = val; };
                      const check = (name, val=true) => { const el = qs(`[name="${name}"]`); if (el) el.checked = !!val; };
                      qs('#preset-gmail')?.addEventListener('click', () => {
                        setVal('name', 'Gmail SMTP');
                        setVal('host', 'smtp.gmail.com');
                        setVal('port', 587);
                        setSel('encryption', 'tls');
                        setVal('from_name', 'Clinic');
                        check('is_active', true);
                      });
                      qs('#preset-m365')?.addEventListener('click', () => {
                        setVal('name', 'Microsoft 365 SMTP');
                        setVal('host', 'smtp.office365.com');
                        setVal('port', 587);
                        setSel('encryption', 'tls');
                        setVal('from_name', 'Clinic');
                        check('is_active', true);
                      });
                    })();
                    </script>
                    @endpush
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
