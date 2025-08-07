<div class="space-y-4">
    <div>
        <label for="host" class="block text-sm font-medium text-gray-700">{{ __('Host') }}</label>
        <input type="text" name="host" id="host" value="{{ old('host', $smtpConfiguration->host ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div>
        <label for="port" class="block text-sm font-medium text-gray-700">{{ __('Port') }}</label>
        <input type="text" name="port" id="port" value="{{ old('port', $smtpConfiguration->port ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div>
        <label for="username" class="block text-sm font-medium text-gray-700">{{ __('Username') }}</label>
        <input type="text" name="username" id="username" value="{{ old('username', $smtpConfiguration->username ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div>
        <label for="password" class="block text-sm font-medium text-gray-700">{{ __('Password') }}</label>
        <input type="password" name="password" id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
    </div>
    <div>
        <label for="encryption" class="block text-sm font-medium text-gray-700">{{ __('Encryption') }}</label>
        <select name="encryption" id="encryption" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <option value="tls" @if (old('encryption', $smtpConfiguration->encryption ?? '') === 'tls') selected @endif>{{ __('TLS') }}</option>
            <option value="ssl" @if (old('encryption', $smtpConfiguration->encryption ?? '') === 'ssl') selected @endif>{{ __('SSL') }}</option>
        </select>
    </div>
    <div class="flex items-center">
        <input type="checkbox" name="is_default" id="is_default" value="1" @if (old('is_default', $smtpConfiguration->is_default ?? false)) checked @endif class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
        <label for="is_default" class="ml-2 block text-sm text-gray-900">{{ __('Set as default') }}</label>
    </div>
</div>
<div class="flex justify-end mt-4">
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ __('Save') }}
    </button>
</div>
