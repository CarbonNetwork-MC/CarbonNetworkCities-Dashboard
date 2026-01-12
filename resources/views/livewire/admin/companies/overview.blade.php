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
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.companies_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="search" wire:model.live="search" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.companies.new') }}">
                    {{ __('admin.buttons.company_create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_world_id') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_coc_number') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_owner') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_bank_accounts') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_employees') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_plots') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.company_pin_consoles') }}</th>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse ($companies as $company)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->world_id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->coc_number }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->owner->username ?? __('admin.labels.no_owner_assigned') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->bankAccounts->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->employees->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->plots->count() }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                {{ $company->pinConsoles->count() }}
                            </td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCompany('{{ $company->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.companies_no_records') }}
                            </td>
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
    <x-modals.modal wire:model="deleteCompanyModal" :title="__('admin.titles.company_delete')">
        <x-slot name="content">
            <p class="text-gray-700 dark:text-gray-300">
                {!! __('admin.messages.company_delete_confirmation', ['name' => $selectedCompany->name ?? '']) !!}
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
