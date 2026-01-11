@props(['href' => null])

@if ($href)
    <a href="{{ $href }}" class="text-red-600 hover:text-red-900">
        {{ $slot }}
    </a>
@else
    <span class="text-red-600 hover:text-red-900 cursor-pointer">
        {{ $slot }}
    </span>
@endif