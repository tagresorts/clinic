@props(['staff'])

<div class="space-y-4">
    @foreach ($staff as $member)
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-full bg-gray-300"></div>
            <div class="ml-4">
                <p class="font-semibold">{{ $member->name }}</p>
                <p class="text-sm text-gray-500">{{ $member->getRoleNames()->first() }}</p>
            </div>
            <div class="ml-auto">
                <span class="px-2 py-1 text-xs font-semibold rounded-full
                    @if ($member->status === 'Available') bg-green-200 text-green-800
                    @elseif ($member->status === 'In Procedure') bg-yellow-200 text-yellow-800
                    @else bg-red-200 text-red-800 @endif">
                    {{ $member->status }}
                </span>
            </div>
        </div>
    @endforeach
</div>
