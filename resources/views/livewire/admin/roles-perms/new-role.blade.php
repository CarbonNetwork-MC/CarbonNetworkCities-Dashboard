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
                'label' => __('admin.buttons.role_create'),
            ],
        ]" />
    </x-slot>

    <x-containers.main>
        <h1 class="text-xl font-semibold dark:text-white mb-4">
            {{ __('admin.buttons.role_create') }}
        </h1>

        <div class="mt-6">
            <div class="grid grid-cols-4">
                {{-- Role name --}}
                <div class="col-span-1">
                    <x-forms.text-input label="{{ __('admin.labels.role_name') }}" wire:model="roleName" placeholder="{{ __('admin.placeholders.role_name') }}" required />
                </div>
            </div>

            <div class="flex justify-end items-center gap-x-4">
                <p class="text-black dark:text-white">
                    {{ __('general.messages.required_fields') }} <span class="text-red-500">*</span>
                </p>
                <x-buttons.primary-button wire:click="createRole">
                    {{ __('general.buttons.create') }}
                </x-buttons.primary-button>
            </div>
        </div>
    </x-containers.main>
</div>
