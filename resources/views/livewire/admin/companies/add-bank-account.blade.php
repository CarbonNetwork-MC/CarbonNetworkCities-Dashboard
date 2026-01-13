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
                'url'   => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ],
            [
                'url'   => route('admin.companies.edit', ['id' => $company->id]),
                'label' => __('admin.titles.company_edit'),
            ],
            [
                'url' => route('admin.companies.add-bank-account', ['id' => $company->id]),
                'label' => __('admin.titles.add_bank_account'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.titles.add_bank_account') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Balance --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.bank_account_balance') }}" wire:model="balance" required />
                </div>

                {{-- Currency --}}
                <div class="col-span-1">
                    <x-forms.label for="currency" required>
                        {{ __('admin.labels.bank_account_currency') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="currency"
                        :options="$currencies->map(fn($currency) => ['value' => $currency, 'label' => $currency])"
                        wire:model="currency"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Is Main --}}
                <div class="col-span-1 mt-6">
                    <x-forms.checkbox
                        id="is_main"
                        label="{{ __('admin.labels.bank_account_is_main') }}"
                        wire:model="isMain"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addBankAccount">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
