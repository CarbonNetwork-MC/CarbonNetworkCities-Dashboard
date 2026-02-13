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
                'url' => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.stock.render', ['companyId' => $company->id]),
                'label' => __('company.titles.stock_overview'),
            ],
            [
                'url'   => route('company.stock.edit.render', ['companyId' => $company->id, 'itemId' => $stockItem->id]),
                'label' => __('company.titles.edit_stock'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <div class="flex items-center gap-x-4">
            <x-containers.title>{{ __('company.titles.edit_stock') }}</x-containers.title>
        </div>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Item Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('company.labels.item') }}" wire:model="itemName" disabled />
                </div>

                <div class="col-span-3"></div>

                {{-- Preferred Stock Level --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('company.labels.preferred_stock_level') }}" wire:model="preferredStockLevel" required />
                </div>

                <div class="col-span-3"></div>

                {{-- Warning Threshold --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('company.labels.warning_threshold') }}" wire:model="warningThreshold" required />
                </div>

                {{-- Critical Threshold --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('company.labels.critical_threshold') }}" wire:model="criticalThreshold" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="save">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
