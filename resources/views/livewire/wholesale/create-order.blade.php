<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        @if (auth()->user()->player->amountOfCompanies() > 1)
            <x-breadcrumbs :items="[
                [
                    'icon' => 'fi fi-rs-house-chimney',
                    'url' => route('dashboard.render'),
                    'label' => '',
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.start', ['step' => 1]),
                    'label' => __('wholesale.titles.start'),
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.create-order', ['wholesalerId' => $wholesaler->id, 'companyId' => $company->id]),
                    'label' => __('wholesale.titles.create_order'),
                ]
            ]" />
        @else
            <x-breadcrumbs :items="[
                [
                    'icon' => 'fi fi-rs-house-chimney',
                    'url' => route('dashboard.render'),
                    'label' => '',
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.create-order', ['wholesalerId' => $wholesaler->id, 'companyId' => $company->id]),
                    'label' => __('wholesale.titles.create_order'),
                ]
            ]" />
        @endif
    </x-slot>

    <div class="flex items-center flex-col">
        <x-containers.main class="grid grid-cols-1 gap-2 w-[50%]">
            <ol class="w-full flex justify-center items-center text-sm font-medium text-center text-body sm:text-base">
                <li class="flex items-center">
                    <div class="flex items-center gap-x-2 text-blue-500">
                        <i class="fi fi-rr-circle-1"></i>
                        <span>{{ __('wholesale.stepper.step1') }}</span>
                        <hr class="w-32 h-1 border-0 rounded-md hidden sm:block bg-blue-500">
                    </div>
                </li>
                <li class="flex items-center ml-2">
                    <div class="flex items-center gap-x-2 text-blue-500">
                        <i class="fi fi-rr-circle-2"></i>
                        <span>{{ __('wholesale.stepper.step2') }}</span>
                        <hr class="w-32 h-1 border-0 rounded-md hidden sm:block bg-blue-500">
                    </div>
                </li>
                <li class="flex items-center ml-2">
                    <div class="flex items-center gap-x-2">
                        <i class="fi fi-rr-circle-3"></i>
                        <span>{{ __('wholesale.stepper.step3') }}</span>
                    </div>
                </li>
            </ol>
        </x-containers.main>
    </div>

    <div class="flex items-center flex-col mt-4">
        <x-containers.main class="grid grid-cols-1 gap-2 w-[50%]">
            <x-containers.title class="mb-2">
                {{ __('wholesale.titles.order_overview') }}
            </x-containers.title>
            
            @foreach ($orderItems as $index => $item)
                <div class="grid grid-cols-3">
                    <div class="col-span-1 flex items-center">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $item['name'] }}
                        </div>
                    </div>
                    
                    <div class="col-span-1">
                        <div class="flex items-center justify-center gap-2">
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }})" 
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $item['amount'] == 0 || empty($item['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus"></i>
                            </button>
    
                            <input 
                                type="number"
                                min="0"
                                max="{{ $item['max_amount'] }}"
                                wire:model.live="orderItems.{{ $index }}.amount"
                                wire:blur="calculatePrice({{ $index }})"
                                class="w-10 text-center text-sm font-medium text-gray-700 dark:text-white appearance-none bg-transparent border border-gray-400 dark:border-gray-600 rounded-md focus:outline-none focus:border-b focus:border-gray-400 hover:cursor-text transition-colors px-1 [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                            >
    
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }})"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $item['amount'] >= $item['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus"></i>
                            </button>
                        </div>
                        @if ($item['amount'] > $item['max_amount'])
                            <span class="flex justify-center text-xs text-red-500 mt-1">Max {{ $item['max_amount'] }}</span>
                        @endif
                    </div>

                    <div class="col-span-1 flex items-center justify-end">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $wholesaler->country->currency_symbol }}{{ number_format($item['total'], 2) }}
                        </div>
                    </div>
                </div>
                <hr class="text-gray-400 dark:text-gray-600">
            @endforeach
        </x-containers.main>

        <x-containers.main class="w-[50%] mt-4">
            <x-containers.title marginBottom="1">
                {{ __('wholesale.titles.order_summary') }}
            </x-containers.title>

            <hr class="text-gray-400 dark:text-gray-600 mb-1.5">

            <div class="grid grid-cols-2">
                <div class="col-span-1 flex items-center">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ __('wholesale.labels.total') }}
                    </div>
                </div>

                <div class="col-span-1 flex items-center justify-end">
                    <div class="text-md font-medium text-gray-700 dark:text-white">
                        {{ $wholesaler->country->currency_symbol }}{{ number_format($total, 2) }}
                    </div>
                </div>

                <div class="col-span-1 flex items-center justify-center mt-5 mr-1.5">
                    <x-buttons.secondary-button class="w-full" x-on:click="window.location='{{ route('wholesale.start', ['step' => 1]) }}'">
                        {{ __('wholesale.buttons.cancel_order') }}
                    </x-buttons.secondary-button>
                </div>

                <div class="col-span-1 flex items-center justify-center mt-5 ml-1.5">
                    <x-buttons.primary-button class="w-full" wire:click="createOrder()">
                        {{ __('wholesale.buttons.finish_order') }}
                    </x-buttons.primary-button>
                </div>
            </div>
        </x-containers.main>
    </div>
</div>
