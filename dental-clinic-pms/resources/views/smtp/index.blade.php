<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('SMTP Configurations') }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('smtp.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded">Add SMTP</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Host</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">From</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Default</th>
                                    <th class="px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($configs as $cfg)
                                <tr>
                                    <td class="px-6 py-4 text-sm">{{ $cfg->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $cfg->host }}:{{ $cfg->port }} ({{ $cfg->encryption ?: 'none' }})</td>
                                    <td class="px-6 py-4 text-sm">{{ $cfg->from_name }} &lt;{{ $cfg->from_email }}&gt;</td>
                                    <td class="px-6 py-4 text-sm">@if($cfg->is_default)<span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Default</span>@endif</td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('smtp.edit', $cfg) }}" class="text-gray-700">Edit</a>
                                        <form action="{{ route('smtp.set-default', $cfg) }}" method="POST" class="inline">@csrf<button class="text-indigo-600" type="submit">Set Default</button></form>
                                        <form action="{{ route('smtp.test', $cfg) }}" method="POST" class="inline">@csrf<input name="to" type="email" placeholder="test@you.com" class="border rounded px-2 py-1 text-xs" required><button class="text-gray-700 ml-1" type="submit">Test</button></form>
                                        <form action="{{ route('smtp.destroy', $cfg) }}" method="POST" class="inline" onsubmit="return confirm('Delete this SMTP?')">@csrf @method('DELETE')<button class="text-red-600" type="submit">Delete</button></form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
