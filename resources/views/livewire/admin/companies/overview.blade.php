<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('admin.dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('admin.companies.render'),
                'label' => __('sidebar.companies'),
            ]
        ]" />
    </x-slot>

    {{-- Companies --}}
    <x-containers.main>
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.company.overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.new') }}">
                    {{ __('admin.buttons.company.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('general.labels.id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.world_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.coc_number') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.owner') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.bank_accounts') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.employees') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.plots') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.company.pin_consoles') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($companies as $company)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $company->id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->world_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->coc_number }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->owner->username ?? __('admin.labels.company.no_owner_assigned') }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->bankAccounts->count() }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->employees->count() }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->plots->count() }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->pinConsoles->count() }}</x-tables.table-data>
                            <x-tables.table-actions>
                                <x-tables.primary-action href="{{ route('admin.companies.edit', ['id' => $company->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCompany('{{ $company->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-actions>
                        </x-tables.table-row>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="9">
                                {{ __('admin.messages.company.companies_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    @if ($companies->hasPages())
                        <div class="w-full flex items-center gap-x-4 mt-4">
                            {{ $companies->links() }}
                            <x-tables.per-page-select wire:model.live="companiesPerPage">
                                <option value="5">5</option>
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </x-tables.per-page-select>
                        </div>
                    @endif
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Delete Company Modal --}}
    <x-modals.modal wire:model="deleteCompanyModal" :title="__('admin.titles.company.delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.company.delete_confirmation', ['name' => $selectedCompany->name ?? '']) !!}
            </p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteCompanyModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyCompany">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>
