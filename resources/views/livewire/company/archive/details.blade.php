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
                'url'   => route('company.archive.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.archive'),
            ],
            [
                'url'   => route('company.archive.details.render', ['companyId' => $company->id, 'saleId' => $sale->id]),
                'label' => __('company.labels.sale_at') . ' ' . $sale->created_at->format('H:i d-m-Y'),
            ]
        ]" />
    </x-slot>

    <div class="flex justify-center">
        <div class="w-[50%]">
            <x-containers.main>
                <div class="flex gap-x-4">
                    <x-forms.text-input label="{{ __('company.labels.customer') }}" wire:model="customer" disabled />
                    <x-forms.text-input label="{{ __('company.labels.employee') }}" wire:model="employee" disabled />
                </div>
            </x-containers.main>
            <x-containers.main class="mt-4">
                @forelse ($sale->items as $item)
                    <div class="grid grid-cols-3 border-b border-gray-400 dark:border-gray-600 {{ $sale->items->count() > 1 ? 'mb-2' : '' }} pb-2">
                        <div class="col-span-1 text-md font-medium text-gray-700 dark:text-white">
                            {{ $item->item->item->name }}
                        </div>
                        <div class="col-span-1 flex justify-center text-md font-medium text-gray-700 dark:text-white">
                            {{ $item->quantity }} {{ __('company.labels.units') }}
                        </div>
                        <div class="col-span-1 flex justify-end text-md font-medium text-gray-700 dark:text-white">
                            {{ $sale->company->country->currency_symbol ?? '' }}{{ number_format($item->price, 2) }}
                        </div>
                    </div>
                @empty

                @endforelse
            </x-containers.main>

            <x-containers.main class="mt-4">
                <x-containers.title>
                    {{ __('company.titles.order_summary') }}
                </x-containers.title>

                <hr class="text-gray-400 dark:text-gray-600 my-2">

                <div class="flex justify-between text-md font-medium text-gray-700 dark:text-white">
                    <span>{{ __('company.labels.total') }}</span>
                    <span>{{ $sale->company->country->currency_symbol ?? '' }}{{ number_format($sale->total_revenue, 2) }}</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 mt-2">
                    <div class="col-span-1"
                        x-data="{
                            copied: false,
                            copy() {
                                const copyText = this.$refs.copyText.innerText;
                                navigator.clipboard.writeText(copyText).then(() => {
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

                            <div class="text-md font-medium text-gray-700 dark:text-white" x-ref="copyText">
                                /pin set {{ $customer ?? '' }} {{ $sale->total_revenue == 0 ? '' : $sale->total_revenue }}
                            </div>
                        </div>
                    </div>
                </div>
            </x-containers.main>
        </div>
    </div>
</div>
