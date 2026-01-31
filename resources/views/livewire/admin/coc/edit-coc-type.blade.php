<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        <x-breadcrumbs :items="[
            [
                'icon' => 'home',
                'url' => route('admin.dashboard.render'),
                'label' => __('sidebar.dashboard'),
            ],
            [
                'icon' => '',
                'url' => route('admin.coc.render'),
                'label' => __('admin.titles.coc.overview'),
            ],
            [
                'icon' => '',
                'url' => route('admin.coc.edit', ['id' => $cocType->id]),
                'label' => __('admin.titles.coc.edit'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.coc.edit') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.coc.name') }}" wire:model="name" required />
                </div>

                <div class="col-span-3"></div>

                {{-- Description --}}
                <div class="col-span-2">
                    <x-forms.text-area label="{{ __('admin.labels.coc.description') }}" wire:model="description" />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updateCoCType">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
