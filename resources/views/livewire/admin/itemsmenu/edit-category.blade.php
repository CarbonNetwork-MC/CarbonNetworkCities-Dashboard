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
                'url'   => route('admin.itemsmenu.render'),
                'label' => __('sidebar.itemsmenu'),
            ],
            [
                'url'   => route('admin.itemsmenu.category.edit', ['id' => $category->id]),
                'label' => __('admin.titles.itemsmenu.edit_category'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.itemsmenu.edit_category') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Category name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.itemsmenu.category_name') }}" wire:model="name" required />
                </div>
                {{-- Icon Material --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.itemsmenu.icon_material') }}" wire:model="iconMaterial" required />
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateCategory">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
