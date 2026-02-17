<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('company.choose.render'),
                'label' => __('sidebar.company.title'),
            ],
            [
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.sales.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.sales'),
            ],
        ]" />
    </x-slot>

    <div class="flex gap-x-4">
        <div class="w-[75%]">
            {{-- customer, products + amount --}}
            <x-containers.main>
                <div class="grid grid-cols-3 gap-x-4">
                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('company.labels.customer') }}" wire:model="customer" required />
                    </div>
                </div>
            </x-containers.main>
            <x-containers.main class="mt-4">
                <div class="grid grid-cols-3 gap-4">
                    @forelse ($sales as $index => $product)
                        <div class="col-span-1 flex items-center text-md font-medium text-gray-700 dark:text-white">
                            {{ $product['item'] }}
                        </div>
                        <div class="col-span-1 flex items-center gap-2">
                            {{-- Decrement 1 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }})" 
                                class="text-gray-400 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-16 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">1</span>
                            </button>

                            {{-- Decrement 32 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }}, 32)" 
                                class="text-gray-400 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">32</span>
                            </button>

                            {{-- Decrement 64 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }}, 64)" 
                                class="text-gray-400 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">64</span>
                            </button>

                            {{-- Amount input --}}
                            <x-forms.number-input wire:model.live="sales.{{ $index }}.amount" wire:blur="calculatePrice('{{ $index }}')" class="w-10" />

                            {{-- Increment 1 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }})"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-16 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus-small"></i> <span class="text-md ms-0.5">1</span>
                            </button>

                            {{-- Increment 32 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }}, 32)"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-16 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus-small"></i> <span class="text-md ms-0.5">32</span>
                            </button>

                            {{-- Increment 64 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }}, 64)"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-16 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus-small"></i> <span class="text-md ms-0.5">64</span>
                            </button>
                        </div>
                        <div class="col-span-1 flex justify-end items-center">
                            <div class="font-medium text-gray-700 dark:text-white">
                                {{ $company->country->currency_symbol ?? '' }}{{ $sales[$index]['price'] ? number_format($sales[$index]['price'], 2) : number_format(0, 2) }}
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
            </x-containers.main>
        </div>
        <div class="w-[25%]">
            {{-- products + price + stock --}}
        </div>
    </div>
</div>
