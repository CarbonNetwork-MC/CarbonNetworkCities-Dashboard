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
                'url'   => route('company.dashboard.render', ['companyId' => $company->id]),
                'label' => $company->name,
            ],
            [
                'url'   => route('company.settings.render', ['companyId' => $company->id]),
                'label' => __('sidebar.company.settings'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('company.titles.settings') }}</x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Salary Scheme (Percentage of own sales, percentage of team sales) --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('company.labels.salary_scheme') }}" wire:model="salaryScheme">
                        <option value="own_sales_percentage">{{ __('company.options.salary_scheme.own_sales_percentage') }}</option>
                        <option value="team_sales_percentage">{{ __('company.options.salary_scheme.team_sales_percentage') }}</option>
                    </x-forms.select>
                </div>

                {{-- Tip Scheme (Per employee, Shared) --}}
                <div class="col-span-1">
                    <x-forms.select label="{{ __('company.labels.tip_scheme') }}" wire:model="tipScheme">
                        <option value="per_employee">{{ __('company.options.tip_scheme.per_employee') }}</option>
                        <option value="shared">{{ __('company.options.tip_scheme.shared') }}</option>
                    </x-forms.select>
                </div>

                {{-- Default Salary Percentage --}}
                <div class="col-span-1">
                    <x-forms.number-input label="{{ __('company.labels.default_salary_percentage') }}" wire:model="defaultSalaryPercentage" min="0" max="100" step="0.01" />
                </div>
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
