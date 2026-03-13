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
                'url'   => route('company.tips.render', ['companyId' => $company->id]),
                'label' => __('company.titles.tips'),
            ],
            [
                'url'   => route('company.tips.new.render', ['companyId' => $company->id]),
                'label' => __('company.buttons.new_tip'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('company.buttons.new_tip') }}</x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1">
                    <x-forms.text-input wire:model="customer" label="{{ __('company.labels.customer') }}" />
                </div>
                <div class="col-span-1">
                    <x-forms.number-input wire:model="amount" label="{{ __('company.labels.amount') }}" step="0.01" min="0" />
                </div>
            </div>
            <div class="flex justify-end items-center gap-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="save">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
