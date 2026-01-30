@props(['marginBottom' => false])

<h1 class="text-xl font-semibold dark:text-white {{ $marginBottom ? 'mb-4' : '' }}">
    {{ $slot }}
</h1>