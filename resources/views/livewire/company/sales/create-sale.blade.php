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
            [
                'url'   => route('company.sales.new.render', ['companyId' => $company->id]),
                'label' => __('company.titles.create_sale'),
            ],
        ]" />
    </x-slot>

    <div class="flex gap-x-4">
        <div class="w-full md:w-[75%]">
            {{-- customer, products + amount --}}
            <x-containers.main>
                <div class="grid grid-cols-4 gap-x-4">
                    <div class="col-span-1">
                        <x-forms.text-input label="{{ __('company.labels.customer') }}" wire:model.live.debounce.300ms="customer" required />
                    </div>
                </div>
            </x-containers.main>
            <x-containers.main class="mt-4">
                <x-containers.title>
                    {{ __('company.titles.products') }}
                </x-containers.title>

                {{-- Products --}}
                @forelse ($sales as $index => $product)
                    <div class="grid grid-cols-3 border-b border-gray-400 dark:border-gray-600 mb-2 pb-2">
                        <div class="col-span-1 flex items-center text-md font-medium text-gray-700 dark:text-white">
                            {{ $product['item'] }}
                        </div>
                        <div class="col-span-1 flex items-center gap-2">
                            {{-- Decrement 1 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }})" 
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-16 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">1</span>
                            </button>

                            {{-- Decrement 32 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }}, 32)" 
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent 
                                {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}
                                "
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">32</span>
                            </button>

                            {{-- Decrement 64 --}}
                            <button 
                                type="button" 
                                wire:click="decrement({{ $index }}, 64)" 
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] == 0 || empty($product['amount']) ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-minus-small"></i> <span class="text-md ms-0.5">64</span>
                            </button>

                            {{-- Amount input --}}
                            <x-forms.number-input wire:model.live="sales.{{ $index }}.amount" wire:blur="calculatePrice('{{ $index }}')" min="0" class="w-10" />

                            {{-- Increment 1 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }})"
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus-small"></i> <span class="text-md ms-0.5">1</span>
                            </button>

                            {{-- Increment 32 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }}, 32)"
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
                                tabindex="-1"
                            >
                                <i class="fi fi-rr-plus-small"></i> <span class="text-md ms-0.5">32</span>
                            </button>

                            {{-- Increment 64 --}}
                            <button 
                                type="button" 
                                wire:click="increment({{ $index }}, 64)"
                                class="text-gray-400 dark:text-gray-200 bg-gray-300 dark:bg-gray-700 hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-20 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white cursor-pointer focus:outline-none focus:ring-0 focus:ring-transparent {{ $product['amount'] >= $product['max_amount'] ? 'invisible' : '' }}"
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
                    </div>
                @empty

                @endforelse
            </x-containers.main>

            {{-- Order Summary --}}
            <x-containers.main class="mt-4">
                <x-containers.title>
                    {{ __('company.titles.order_summary') }}
                </x-containers.title>

                <hr class="text-gray-400 dark:text-gray-600 my-2">

                <div class="grid grid-cols-2 gap-4">
                    <div class="col-span-1 flex items-center">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.total') }}
                        </div>
                    </div>

                    <div class="col-span-1 flex items-center justify-end">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ $company->country->currency_symbol ?? '' }}{{ number_format($total, 2) }}
                        </div>
                    </div>

                    <div class="col-span-1"
                        x-data="{
                            copied: false,
                            copy() {
                                const code = this.$refs.code.innerText;
                                navigator.clipboard.writeText(code).then(() => {
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 2000);
                                });
                            }
                        }"
                    >
                        <div class="flex flex-row-reverse items-center justify-between bg-gray-200 dark:bg-gray-900 rounded-md px-4 py-2">
                            <button class="flex items-center gap-2 px-3 py-1.5 text-sm bg-gray-800 hover:bg-gray-700 text-white rounded-md transition cursor-pointer" @click="copy()">
                                <i class="fi fi-rr-clone text-lg"></i>
                            </button>

                            <div class="text-md font-medium text-gray-700 dark:text-white" x-ref="code">
                                /pin set {{ $customer ?? '' }} {{ $total == 0 ? '' : $total }}
                            </div>
                        </div>
                    </div>

                    <div class="col-span-1 flex items-center justify-end">
                        <x-buttons.primary-button wire:click="createSale()">
                            {{ __('company.buttons.create_sale') }}
                        </x-buttons.primary-button>
                    </div>
                </div>
            </x-containers.main>
        </div>
        <div class="w-full md:w-[25%]" x-data="data()" x-init="init()">
            {{-- products + price + stock --}}
            <x-containers.main>
                <div class="grid grid-cols-4 gap-2">
                    <div class="col-span-2">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.product') }}
                        </div>
                    </div>
                    <div class="col-span-1 flex justify-center">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.price') }}
                        </div>
                    </div>
                    <div class="col-span-1 flex justify-end">
                        <div class="text-md font-medium text-gray-700 dark:text-white">
                            {{ __('company.labels.stock') }}
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    @forelse ($products as $product)
                        <div class="grid grid-cols-4 gap-2 border-b border-gray-400 dark:border-gray-600 mb-2 pb-2">
                            <div class="col-span-2">
                                <div class="text-md font-medium text-gray-700 dark:text-white">
                                    {{ $product->item->name }}
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-center">
                                <div class="text-md text-gray-700 dark:text-white">
                                    {{ $company->country->currency_symbol ?? '' }}{{ $product->price }}
                                </div>
                            </div>
                            <div class="col-span-1 flex justify-end">
                                <div 
                                    class="text-md {{ $product->stock->quantity > $product->stock->warning_threshold ? 'text-green-400' 
                                    : ($product->stock->quantity > $product->stock->critical_threshold ? 'text-yellow-400' : 'text-red-400') }}"
                                >
                                    {{ $product->stock->quantity }}
                                </div>
                            </div>
                        </div>
                    @empty

                    @endforelse
                </div>
            </x-containers.main>

            {{-- Item Calculator --}}
            <x-containers.main class="mt-4">
                <div class="flex justify-between">
                    <x-containers.title>
                        {{ __('company.titles.item_calculator') }}
                    </x-containers.title>
                    <div class="flex justify-end text-black dark:text-white text-xl hover:bg-gray-200 dark:hover:bg-gray-700 rounded-md mt-1" @click="toggleGroup('from_total')">
                        <i class="fi fi-rr-angle-small-down cursor-pointer" x-show="!isGroupOpen('from_total')"></i>
                        <i class="fi fi-rr-angle-small-up cursor-pointer" x-show="isGroupOpen('from_total')"></i>
                    </div>
                </div>

                <div 
                    class="grid grid-cols-2 gap-2 overflow-hidden"
                    x-show="isGroupOpen('from_total')"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 -translate-y-1"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-1"
                >
                    <div class="col-span-1">
                        <x-forms.number-input label="{{ __('company.labels.total') }}" wire:model="calculatorTotal" wire:blur="calculateFromTotal" class="w-full" />
                    </div>
                    <div class="col-span-1 flex justify-end items-start">
                        <x-buttons.tertiary-button wire:click="resetCalculator" class="mt-6">
                            <i class="fi fi-rr-refresh"></i>
                        </x-buttons.tertiary-button>
                    </div>
                    <div class="col-span-1">
                        <x-forms.number-input label="{{ __('company.labels.stacks') }}" wire:model="stacks" wire:blur="calculateToTotal" class="w-full" />
                    </div>
                    <div class="col-span-1">
                        <x-forms.number-input label="{{ __('company.labels.items') }}" wire:model="items" wire:blur="calculateToTotal" class="w-full" />
                    </div>
                </div>
            </x-containers.main>
        </div>
    </div>

    <script>
        function data() {
            const STORAGE_KEY = 'sales:open';
            return {
                open: new Set(),
                init() {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) {
                        this.open = new Set(JSON.parse(saved));
                    }
                },
                toggleGroup(group) {
                    if (this.open.has(group)) {
                        this.open.delete(group);
                    } else {
                        this.open.add(group);
                    }
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(this.open)));
                },
                isGroupOpen(group) {
                    return this.open.has(group);
                },
            }
        }
    </script>
</div>
