<div>
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('admin.dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('admin.countries.render'),
                'label' => __('sidebar.countries'),
            ],
            [
                'icon' => '',
                'url' => route('admin.countries.new'),
                'label' => __('admin.buttons.countries.create'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.buttons.countries.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4">
                {{-- Country Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.countries.name') }}" wire:model="countryName" required />
                </div>

                {{-- ISO --}}
                <div class="col-span-1">
                    <x-forms.text-input label="ISO" wire:model="iso" required />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-4 mt-4">
                {{-- Code --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Code" wire:model="code" required />
                </div>

                {{-- HeadDB ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="HeadDB ID" wire:model="headdbId" />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-4 mt-4">
                {{-- Currency --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.countries.currency') }}" wire:model="currency" />
                </div>

                {{-- Currency Symbol --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.countries.currency_symbol') }}" wire:model="currencySymbol" />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-4 mt-4">
                {{-- Currency before amount --}}
                <x-forms.select wire:model="currencyBeforeAmount" label="{{ __('admin.labels.countries.currency_before_amount') }}" required>
                    <option value="">{{ __('general.placeholders.select_option') }}</option>
                    <option value="1">{{ __('general.yes') }}</option>
                    <option value="0">{{ __('general.no') }}</option>
                </x-forms.select>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createCountry">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
