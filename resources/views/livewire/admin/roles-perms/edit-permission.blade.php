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
                'url'   => route('admin.roles-perms.permission.edit', ['uuid' => $permission->uuid]),
                'label' => __('admin.titles.permission_edit'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('general.buttons.edit') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4">
                {{-- Permission name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.permission_name') }}" wire:model="permissionName" placeholder="{{ __('admin.placeholders.permission_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <x-forms.required-fields />
                <x-buttons.primary-button wire:click="updatePermission">
                    {{ __('general.buttons.save') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>