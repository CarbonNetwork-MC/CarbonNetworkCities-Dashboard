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
                'url'   => route('admin.players.render'),
                'label' => __('sidebar.players'),
            ],
            [
                'url'   => route('admin.players.edit', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.edit'),
            ],
            [
                'url'   => route('admin.players.edit-prefix', ['uuid' => $player->uuid, 'id' => $selectedPrefix->id]),
                'label' => __('admin.titles.players.edit_prefix'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.players.edit_prefix') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Prefix --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.players.prefix') }}" wire:model="prefix" required />
                </div>

                <div class="col-span-3"></div>

                {{-- Selected --}}
                <div class="col-span-1 mt-6">
                    <x-forms.checkbox
                        id="selected"
                        label="{{ __('admin.labels.players.selected') }}"
                        wire:model="selected"
                    />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updatePrefix">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
