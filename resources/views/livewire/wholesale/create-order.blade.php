<div>
    {{-- Breadcrumbs --}}
    <x-slot name="breadcrumbs">
        @if (auth()->user()->player->amountOfCompanies() > 1)
            <x-breadcrumbs :items="[
                [
                    'icon' => 'fi fi-rs-house-chimney',
                    'url' => route('dashboard.render'),
                    'label' => '',
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.choose-company'),
                    'label' => __('wholesale.titles.choose_company'),
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.create-order', ['companyId' => $company->id]),
                    'label' => __('wholesale.titles.create_order'),
                ]
            ]" />
        @else
            <x-breadcrumbs :items="[
                [
                    'icon' => 'fi fi-rs-house-chimney',
                    'url' => route('dashboard.render'),
                    'label' => '',
                ],
                [
                    'icon' => '',
                    'url' => route('wholesale.create-order', ['companyId' => $company->id]),
                    'label' => __('wholesale.titles.create_order'),
                ]
            ]" />
        @endif
    </x-slot>
</div>
