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
                'url'   => route('admin.bank-accounts.render'),
                'label' => __('sidebar.bank_accounts'),
            ],
            [
                'url'   => route('admin.bank-accounts.company.new'),
                'label' => __('admin.titles.bank_accounts.company.create'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.bank_accounts.company.create') }}
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

                {{-- Currency --}}
                <div class="col-span-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2.5">
                        {{ __('admin.labels.countries.currency') }}
                    </label>
                    <livewire:async-select
                        :options="$countries->map(fn($country) => ['label' => $country->name . ' - ' . $country->currency, 'value' => $country->currency])"
                        wire:model.live="currency"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Is main --}}
                <div class="col-span-1">
                    <x-forms.checkbox
                        id="is_main"
                        label="{{ __('admin.labels.companies.bank_account_is_main') }}"
                        wire:model="isMain"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createCompanyBankAccount">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
