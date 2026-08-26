<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('wholesale.manage'),
                'label' => __('wholesale.titles.wholesalers'),
            ],
            [
                'url' => route('wholesale.manage'),
                'label' => __('wholesale.titles.select_wholesaler'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('wholesale.titles.choose_wholesaler') }}</x-containers.title>
        <p class="text-gray-600">{{ __('wholesale.messages.select_wholesaler_description') }}</p>
    </x-containers.main>

    <div class="grid grid-cols-3 gap-4 mt-4">
        @forelse ($wholesalers as $wholesaler)
            <x-containers.main>
                <a href="{{ route('wholesale.order-overview', ['wholesalerId' => $wholesaler->id]) }}" class="text-lg font-semibold text-heading hover:text-blue-500">
                    {{ $wholesaler->name }}
                </a>
                
                <div class="col-span-1">
                    <p class="text-sm text-gray-800 dark:text-gray-100">{{ __('wholesale.labels.country') }}: 
                        <span class="text-gray-600 dark:text-gray-400">{{ $wholesaler->country->name }}</span>
                    </p>
                </div>
            </x-containers.main>
        @empty
            <div class="col-span-3 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm">
                <p class="text-gray-600 text-center">{{ __('wholesale.messages.no_wholesalers_available') }}</p>
            </div>
        @endforelse
    </div>
</div>
