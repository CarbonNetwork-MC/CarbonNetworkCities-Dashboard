<div>
    {{-- Breacrumbs --}}
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
                'url' => route('admin.companies.add-plot', ['id' => $company->id]),
                'label' => __('admin.titles.companies.add_plot'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.companies.add_plot') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4 gap-y-6">
                {{-- Plot ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.companies.plot_id') }}" wire:model="plotId" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addPlot">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
