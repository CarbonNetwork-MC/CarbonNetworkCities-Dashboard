<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url'  => route('admin.dashboard.render'),
                'label'=> '',
            ],
            [
                'url'   => route('admin.pin-consoles.render'),
                'label' => __('sidebar.pin_consoles'),
            ],
            [
                'url'   => route('admin.pin-consoles.new'),
                'label' => __('admin.titles.pin_consoles.create'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.pin_consoles.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Company ID --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2.5">
                        {{ __('admin.labels.companies.company_id') }}
                    </label>
                    <livewire:async-select
                        :options="$companies->map(fn($company) => ['label' => $company->name, 'value' => $company->id])"
                        wire:model.live="companyId"
                        :min-search-length="2"
                    />
                </div>

                {{-- Account ID --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2.5">
                        {{ __('admin.labels.companies.bank_account_number') }}
                    </label>
                    <livewire:async-select
                        id="accountSelect"
                        wire:key="accounts-{{ $companyId }}"
                        :options="$accounts->map(fn($account) => ['label' => $account->id, 'value' => $account->id])"
                        wire:model="accountId"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- X --}}
                <div class="col-span-1">
                    <x-forms.text-input label="X" wire:model="x" required />
                </div>

                {{-- Y --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Y" wire:model="y" required />
                </div>

                {{-- Z --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Z" wire:model="z" required />
                </div>

                <div class="col-span-1"></div>

                {{-- City --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.city') }}" wire:model="city" required />
                </div>

                {{-- Country --}}
                <div class="col-span-1">
                    <x-forms.label for="country" required>
                        {{ __('admin.labels.companies.country') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="country"
                        :options="$countries->map(fn($country) => ['value' => $country->id, 'label' => $country->name])"
                        wire:model="countryId"
                        :min-search-length="2"
                    />
                </div>

                {{-- World Name/ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.world_id') }}" wire:model="worldId" required />
                </div>

                <div class="col-span-1"></div>

                {{-- Is active --}}
                <div class="col-span-1">
                    <x-forms.checkbox
                        id="is_active"
                        label="{{ __('admin.labels.companies.pin_console_is_active') }}"
                        wire:model="isActive"
                    />
                </div>

            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createPinConsole">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
