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
                'url'   => route('admin.itemsmenu.render'),
                'label' => __('sidebar.itemsmenu'),
            ],
            [
                'url'   => route('admin.itemsmenu.category.edit', ['id' => $category->id]),
                'label' => __('admin.buttons.category_edit'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.category_edit') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Category name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.category_name') }}" wire:model="name" required />
                </div>
                {{-- Icon Material --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.icon_material') }}" wire:model="iconMaterial" required />
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-x-4 mt-6">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="updateCategory">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
