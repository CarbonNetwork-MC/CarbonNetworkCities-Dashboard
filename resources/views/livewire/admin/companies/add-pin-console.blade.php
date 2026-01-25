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
                'url'   => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ],
            [
                'url'   => route('admin.companies.edit', ['id' => $company->id]),
                'label' => __('admin.titles.company.edit'),
            ],
            [
                'url' => route('admin.companies.add-bank-account', ['id' => $company->id]),
                'label' => __('admin.titles.company.add_bank_account'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.company.add_bank_account') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Company ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.company_id') }}" wire:model="companyId" required />
                </div>

                {{-- Account ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.bank_account_number') }}" wire:model="accountId" required />
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
                    <x-forms.text-input label="{{ __('admin.labels.company.city') }}" wire:model="city" required />
                </div>

                {{-- Country --}}
                <div class="col-span-1">
                    <x-forms.label for="country" required>
                        {{ __('admin.labels.company.country') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="country"
                        :options="$countries->map(fn($country) => ['value' => $country->id, 'label' => $country->name])"
                        wire:model="country"
                        :min-search-length="2"
                    />
                </div>

                {{-- World Name/ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.company.world_id') }}" wire:model="worldId" required />
                </div>

                <div class="col-span-1"></div>

                {{-- Is active --}}
                <div class="col-span-1">
                    <x-forms.checkbox
                        id="is_active"
                        label="{{ __('admin.labels.company.pin_console_is_active') }}"
                        wire:model="isActive"
                    />
                </div>

            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addPinConsole">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
