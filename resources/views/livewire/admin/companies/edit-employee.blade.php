<div>
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
                'label' => __('admin.titles.companies.edit'),
            ],
            [
                'url' => route('admin.companies.edit-employee', ['companyId' => $company->id, 'playerUuid' => $employee->player_uuid]),
                'label' => __('admin.titles.companies.edit_employee'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.companies.edit_employee') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Role --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('admin.labels.companies.employee_role') }}" wire:model="role" required>
                        <option value="">{{ __('admin.placeholders.companies.select_role') }}</option>
                        @foreach($roles as $role)
                            <option value="{{ $role }}">{{ ucfirst($role) }}</option>
                        @endforeach
                    </x-forms.select>
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateEmployee">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
