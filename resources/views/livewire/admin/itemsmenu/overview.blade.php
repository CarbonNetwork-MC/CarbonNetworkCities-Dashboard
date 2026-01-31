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
                'url'   => route('admin.itemsmenu.render'),
                'label' => __('sidebar.itemsmenu'),
            ],
        ]" />
    </x-slot>

    {{-- Categories --}}
    <x-containers.main>
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.itemsmenu.categories_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchCategory" wire:model.live="searchCategory" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.itemsmenu.category.new') }}">
                    {{ __('admin.buttons.itemsmenu.create_category') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.category_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.icon_material') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.items_amount') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.player_username') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.user_name') }}</x-tables.table-header>
                        <th></th>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($categories as $category)
                        <tr class="odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->icon_material }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ count($category->items) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->player->username ?? null }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $category->user->name ?? null}}</td>
                            <td class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.itemsmenu.category.edit', ['id' => $category->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeCategory('{{ $category->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <x-tables.empty-state colspan="6">
                                {{ __('admin.messages.itemsmenu.categories_no_records') }}
                            </x-tables.empty-state>
                        </tr>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $categories->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Items --}}
    <x-containers.main class="mt-4">
        <div class="flex justify-between">
            <h1 class="text-xl font-semibold dark:text-white mb-4">{{ __('admin.titles.itemsmenu.items_overview') }}</h1>
            <div class="flex items-center gap-x-4">
                <x-forms.search-bar id="searchItem" wire:model.live="searchItem" class="w-full" />
                <x-buttons.primary-button size="sm" href="{{ route('admin.itemsmenu.item.new') }}">
                    {{ __('admin.buttons.itemsmenu.create_item') }}
                </x-buttons.primary-button>
            </div>
        </div>

        <div class="mt-6">
            <x-tables.table-striped>
                <x-slot name="headers">
                    <tr>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.internal_id') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.item_name') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.icon_material') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.category') }}</x-tables.table-header>
                        <x-tables.table-header>Data</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.player_username') }}</x-tables.table-header>
                        <x-tables.table-header>{{ __('admin.labels.itemsmenu.user_name') }}</x-tables.table-header>
                        <x-tables.table-header></x-tables.table-header>
                    </tr>
                </x-slot>
                <x-slot name="rows">
                    @forelse($items as $item)
                        <x-tables.table-row>
                            <x-tables.table-data>{{ $item->internal_id }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->name }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->material }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->category->name }}</x-tables.table-data>
                            <x-tables.table-data 
                                class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white"
                                title="{{ $item->data ? json_encode($item->data, JSON_UNESCAPED_UNICODE) : '' }}"
                            >
                                {{ $item->data ? Str::limit(json_encode($item->data, JSON_UNESCAPED_UNICODE), 30, '...') : '' }}
                            </x-tables.table-data>
                            <x-tables.table-data>{{ $item->player->username ?? null }}</x-tables.table-data>
                            <x-tables.table-data>{{ $item->user->name ?? null }}</x-tables.table-data>
                            <x-tables.table-data class="px-6 py-4 flex justify-end gap-x-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-tables.primary-action href="{{ route('admin.itemsmenu.item.edit', ['id' => $item->id]) }}">{{ __('general.buttons.edit') }}</x-tables.primary-action>
                                <x-tables.danger-action wire:click="removeItem('{{ $item->id }}')">{{ __('general.buttons.delete') }}</x-tables.danger-action>
                            </x-tables.table-data>
                        </x-tables.table-row>
                    @empty
                        <x-tables.table-row>
                            <x-tables.empty-state colspan="7">
                                {{ __('admin.messages.itemsmenu.items_no_records') }}
                            </x-tables.empty-state>
                        </x-tables.table-row>
                    @endforelse
                </x-slot>
                <x-slot name="pagination">
                    {{ $items->links() }}
                </x-slot>
            </x-tables.table-striped>
        </div>
    </x-containers.main>

    {{-- Delete Category Modal --}}
    <x-modals.modal wire:model="deleteCategoryModal">
        <x-slot name="title">{{ __('admin.titles.itemsmenu.delete_category') }}</x-slot>
        <x-slot name="content">
            @if ($selectedCategory && $selectedCategory->items->count() > 0)
                <p class="mb-2">{!! __('admin.messages.itemsmenu.category_delete_confirmation_with_items', ['name' => $selectedCategory ? $selectedCategory->name : '']) !!}</p>
                <x-forms.select id="categorySelect" wire:model="categoryId" label="{{ __('admin.labels.itemsmenu.change_category') }}">
                    <option value="">{{ __('general.placeholders.select_option') }}</option>
                    @foreach($remainingCategories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </x-forms.select>
            @else
                <p class="mb-2">{!! __('admin.messages.itemsmenu.category_delete_confirmation_without_items', ['name' => $selectedCategory ? $selectedCategory->name : '']) !!}</p>
            @endif
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyCategory" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.buttons.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>

    {{-- Delete Item Modal --}}
    <x-modals.modal wire:model="deleteItemModal">
        <x-slot name="title">{{ __('admin.titles.itemsmenu.delete_item') }}</x-slot>
        <x-slot name="content">
            <p>{!! __('admin.messages.itemsmenu.item_delete_confirmation', ['name' => $selectedItem ? $selectedItem->name : '']) !!}</p>
        </x-slot>
        <x-slot name="footer">
            <button type="button" @click="$dispatch('close')" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cursor-pointer">
                {{ __('general.buttons.cancel') }}
            </button>
            <button type="button" wire:click="destroyItem" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 cursor-pointer">
                {{ __('general.buttons.delete') }}
            </button>
        </x-slot>
    </x-modals.modal>
</div>
