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
            [
                'url' => route('company.employees.edit.render', ['companyId' => $company->id, 'employeeId' => $employee->player_uuid]),
                'label' => $employee->player->username,
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('company.titles.edit_employee') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('company.labels.salary_percentage') }}" wire:model="salaryPercentage" min="0" max="100" step="0.01" />
                    
                    @if ($company->settings->default_salary_percentage !== $employee->salary_percentage)
                        <p class="text-sm text-sky-500 hover:text-sky-600 mt-1 cursor-pointer" wire:click="syncDefaultSalaryPercentage">
                            {{ __('company.messages.default_salary_percentage', ['percentage' => $company->settings->default_salary_percentage]) }}
                        </p>
                    @endif
                </div>

                <div class="col-span-1 ml-6 mt-10">
                    <x-forms.checkbox label="{{ __('company.labels.is_paid')}}" wire:model="isPaid" />
                </div>

                <div class="col-span-2"></div>
            </div>

            <div class="flex justify-end items-center gap-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="save">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
