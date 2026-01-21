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
                'url'   => route('admin.roles-perms.render'),
                'label' => __('sidebar.roles_perms'),
            ],
            [
                'url'   => route('admin.roles-perms.role.new'),
                'label' => __('admin.buttons.roles.create'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <x-containers.title>
            {{ __('admin.buttons.roles.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4">
                {{-- Role name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.role.name') }}" wire:model="roleName" placeholder="{{ __('admin.placeholders.roles.role_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createRole">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
