@props(['avatar'])

<div class="flex items-center gap-x-2">
    <img src="{{ $avatar }}" class="w-6 h-6 rounded-full object-cover" />
    <span>{{ $slot }}</span>
</div>
