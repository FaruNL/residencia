@props(['value'])

<div class="block px-4 py-2 text-xs text-gray-400">
    {{ $value ?? $slot }}
</div>
