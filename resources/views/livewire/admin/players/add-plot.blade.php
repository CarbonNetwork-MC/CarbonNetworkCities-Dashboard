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
                'url'   => route('admin.players.add-plot', ['uuid' => $player->uuid]),
                'label' => __('admin.titles.players.add_plot'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.titles.players.add_plot') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-4">
                {{-- Plot --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.players.plot_id') }}" wire:model="plotId" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="addPlot">
                    {{ __('general.buttons.add') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
