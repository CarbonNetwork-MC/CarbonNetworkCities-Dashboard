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
        ]" />
    </x-slot>

    {{-- Roles --}}
    <x-containers.main>
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.languages_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchLanguage" wire:model.live="searchLanguage" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.languages.new') }}">
                    {{ __('admin.buttons.language_create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">{{ __('admin.labels.language_name') }}</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Shortcode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-900 dark:text-white uppercase tracking-wider">HeadDB ID</th>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($languages as $language)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $language->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $language->short_code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $language->code }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $language->headdb_id }}</td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.languages.edit', ['id' => $language->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeLanguage('{{ $language->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                {{ __('admin.messages.languages_no_records') }}
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $languages->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Delete Language Modal --}}
    <x-modals.modal wire:model="deleteLanguageModal">
        <x-slot name="title">{{ __('admin.titles.language_delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.languages_modal_delete_confirmation', ['name' => $selectedLanguage ? $selectedLanguage->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyLanguage" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.buttons.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>
</div>