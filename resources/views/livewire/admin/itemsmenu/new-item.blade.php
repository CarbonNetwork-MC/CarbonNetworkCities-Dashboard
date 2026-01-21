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
                'url'   => route('admin.itemsmenu.item.new'),
                'label' => __('admin.buttons.item_create'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.item_create') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Internal ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.internal_id') }}" wire:model="internalId" required />
                </div>
                {{-- Icon name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.item_name') }}" wire:model="name" required />
                </div>
            </div>

            <div class="grid grid-cols-4 gap-x-6 mt-4">
                {{-- Item name --}}
                <div class="col-span-1">
                    <x-forms.select id="categorySelect" wire:model="categoryId" label="{{ __('admin.labels.category') }}">
                        <option value="">{{ __('general.placeholders.select_option') }}</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-forms.select>
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
                <x-buttons.primary-button wire:click="createItem">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
