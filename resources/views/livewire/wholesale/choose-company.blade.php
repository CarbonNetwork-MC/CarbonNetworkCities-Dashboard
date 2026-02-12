<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'fi fi-rs-house-chimney',
                'url' => route('dashboard.render'),
                'label' => '',
            ],
            [
                'icon' => '',
                'url' => route('wholesale.choose-company'),
                'label' => __('wholesale.titles.choose_company'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.players.companies') }}</x-containers.title>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.companies.name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.coc_number') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.coc_type') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.companies.owner') }}</x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @foreach ($companies as $company)
                        <x-tables.table-row wire:click="selectCompany({{ $company->id }})" class="cursor-pointer">
                            <x-tables.table-data>{{ $company->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->coc_number }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->coc_type ?? '-' }}</x-tables.table-data>
                            <x-tables.table-data>{{ $company->owner->username ?? __('admin.labels.companies.no_owner_assigned') }}</x-tables.table-data>
                        </x-tables.table-row>
                    @endforeach
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>
</div>
