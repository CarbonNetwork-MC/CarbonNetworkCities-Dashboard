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
                'label' => __('admin.buttons.permission_create'),
            ]
        ]" />
    </x-slot>
    
    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.permission_create') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4 gap-x-6">
                {{-- Permission name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.permission_name') }}" wire:model="permissionName" placeholder="{{ __('admin.placeholders.permission_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4 mt-6">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="createPermission">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
