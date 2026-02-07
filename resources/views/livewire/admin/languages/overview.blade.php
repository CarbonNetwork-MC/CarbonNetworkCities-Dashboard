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
                'url'   => route('admin.languages.render'),
                'label' => __('sidebar.languages'),
            ],
        ]" />
    </x-slot>

    {{-- Roles --}}
    <x-containers.main>
        <div class="flex justify-between">
            <x-containers.title>{{ __('admin.titles.languages.overview') }}</x-containers.title>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchLanguage" wire:model.live="searchLanguage" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.languages.new') }}">
                    {{ __('admin.buttons.languages.create') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.languages.name') }}</x-tables.table-header>
                        <x-tables.table-header>Shortcode</x-tables.table-header>
                        <x-tables.table-header>Code</x-tables.table-header>
                        <x-tables.table-header>HeadDB ID</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($languages as $language)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $language->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $language->short_code }}</x-tables.table-data>
                            <x-tables.table-data>{{ $language->code }}</x-tables.table-data>
                            <x-tables.table-data>{{ $language->headdb_id }}</x-tables.table-data>
                            <x-tables.table-data class="flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.languages.edit', ['id' => $language->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeLanguage('{{ $language->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="5">
                                {{ __('admin.messages.languages.languages_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
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
        <x-slot name="title">{{ __('admin.titles.languages.delete') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.languages.delete_confirmation', ['name' => $selectedLanguage ? $selectedLanguage->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <x-buttons.secondary-button wire:click="$set('deleteLanguageModal', false)">
                {{ __('general.buttons.cancel') }}
            </x-buttons.secondary-button>
            <x-buttons.danger-button wire:click="destroyLanguage">
                {{ __('general.buttons.delete') }}
            </x-buttons.danger-button>
        </x-slot>
    </x-modals.modal>
</div>