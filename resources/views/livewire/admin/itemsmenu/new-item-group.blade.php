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
                'url'   => route('admin.itemsmenu.item-group.new'),
                'label' => __('admin.titles.itemsmenu.create_item_group'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.itemsmenu.create_item_group') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.itemsmenu.name') }}" wire:model="name" required />
                </div>
                
                {{-- CoC Type --}}
                <div class="col-span-1">
                    <x-forms.label id="cocTypeSelect" required>
                        {{ __('admin.labels.itemsmenu.select_coc_type') }}
                    </x-forms.label>
                    <livewire:async-select
                        id="cocTypeSelect"
                        :options="$cocTypes->map(fn($type) => ['value' => $type->id, 'label' => $type->name])"
                        wire:model.live="cocType"
                        :min-search-length="2"
                    />
                </div>

                <div class="col-span-2"></div>

                {{-- Sellable --}}
                <div class="col-span-1 flex items-end mt-3">
                    <x-forms.checkbox label="{{ __('admin.labels.itemsmenu.sellable') }}" wire:model="sellable" />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createItemGroup">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
