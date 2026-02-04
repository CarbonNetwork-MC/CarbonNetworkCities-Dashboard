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
                'url' => route('admin.companies.add-item-group', ['id' => $company->id]),
                'label' => __('admin.titles.companies.add_item_group'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>{{ __('admin.titles.companies.add_item_group') }}</x-containers.title>

        <div class="mt-6">
            
        </div>
    </x-containers.main>
</div>
