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
                'url'   => route('company.employees.render', ['companyId' => $company->id]),
                'label' => __('company.titles.employees'),
            ],
        ]" />
    </x-slot>

    <div class="flex justify-center">
        <x-containers.main class="w-[50%] md:w-[75%]">
            <x-containers.title>{{ __('company.titles.employees') }}</x-containers.title>

            <div class="mt-6">
                <x-tables.table-striped>
                    <x-slot name="headers">
                        <tr>
                            <th></th>
                            <x-tables.table-header>{{ __('company.labels.name') }}</x-tables.table-header>
                            <x-tables.table-header>{{ __('company.labels.role') }}</x-tables.table-header>
                            @if ($hasPermission) <th></th> @endif
                        </tr>
                    </x-slot>
                    <x-slot name="rows">
                        @forelse ($employees as $employee)
                            <x-tables.table-row>
                                <x-tables.table-data><img class="h-8 w-8" src="https://cravatar.eu/avatar/{{ $employee['uuid'] }}/64.png" /></x-tables.table-data>
                                <x-tables.table-data>{{ $employee['username'] }}</x-tables.table-data>
                                <x-tables.table-data>{{ __('company.roles.' . $employee['role']) }}</x-tables.table-data>
                                @if ($hasPermission)
                                    <x-tables.table-actions>
                                        @if ($employee['role'] != 'owner')
                                            <x-tables.primary-action href="{{ route('company.employees.edit.render', ['companyId' => $company->id, 'employeeId' => $employee['uuid']]) }}">
                                                {{ __('general.buttons.edit') }}
                                            </x-tables.primary-action>
                                        @endif
                                    </x-tables.table-actions>
                                @endif
                            </x-tables.table-row>
                        @empty
                            <x-tables.table-row>
                                <x-tables.empty-state :colspan="4">
                                    {{ __('company.messages.no_employees') }}
                                </x-tables.empty-state>
                            </x-tables.table-row>
                        @endforelse
                    </x-slot>
                </x-tables.table-striped>
            </div>
        </x-containers.main>
    </div>
</div>
