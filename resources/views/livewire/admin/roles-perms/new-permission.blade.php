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
                'url'   => route('admin.roles-perms.permission.new'),
                'label' => __('admin.buttons.permissions.create'),
            ]
        ]" />
    </x-slot>
    
    <x-containers.main>
        <x-containers.title>
            {{ __('admin.buttons.permissions.create') }}
        </x-containers.title>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-4">
                {{-- Permission name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.permissions.name') }}" wire:model="permissionName" placeholder="{{ __('admin.placeholders.permissions.permission_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="createPermission">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
