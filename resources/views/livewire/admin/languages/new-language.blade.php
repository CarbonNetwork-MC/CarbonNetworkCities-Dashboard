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
                'url'   => route('admin.languages.render'),
                'label' => __('sidebar.languages'),
            ],
            [
                'url'   => route('admin.languages.new'),
                'label' => __('admin.buttons.language_create'),
            ]
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.language_create') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Language name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.language_name') }}" wire:model="name" required />
                </div>
                {{-- Shortcode --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Shortcode" wire:model="shortCode" required />
                </div>
            </div>
            
            <div class="grid grid-cols-4 gap-x-6 mt-6">
                {{-- Code --}}
                <div class="col-span-1">
                    <x-forms.text-input label="Code" wire:model="code" required />
                </div>
                {{-- HeadDB ID --}}
                <div class="col-span-1">
                    <x-forms.text-input label="HeadDB ID" wire:model="headdbId" />
                </div>
            </div>
            
            <div class="flex justify-end items-center gap-x-4 mt-6">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="createLanguage">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
