@blaze

@props(['avatar'])

<div class="flex items-center gap-x-2">
    <img src="{{ $avatar }}" class="w-6 h-6 [.fi-badge-label-ctn_&]:w-4 [.fi-badge-label-ctn_&]:h-4 rounded-full object-cover" />
    <div class="min-w-0 truncate">{{ $slot }}</div>
</div>
