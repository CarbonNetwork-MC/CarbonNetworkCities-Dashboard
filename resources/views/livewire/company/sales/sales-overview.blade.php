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
                <div class="grid grid-cols-3 gap-x-4">
                    @forelse ($products as $index => $product)
                        <div class="col-span-1 text-md font-medium text-gray-700 dark:text-white">
                            {{ $product->item->name }}
                        </div>
                        <div class="col-span-1">
                            <x-forms.number-input wire:model="sales.{{ $index }}.amount" />
                        </div>
                        <div class="col-span-1"></div>
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
