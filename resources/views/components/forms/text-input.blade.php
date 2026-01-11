@props(['id' => 'text-input', 'label' => '', 'size' => 'md', 'placeholder' => '', 'required' => false, 'disabled' => false])
@php
    $sizeClasses = match($size) {
        'sm' => 'px-2.5 py-2',
        'md' => 'px-3 py-2.5',
        'lg' => 'px-3.5 py-3',
        'xl' => 'px-4 py-3.5',
        default => 'md',
    };

    $wrapperAttributes = $attributes->only(['wrapper:class']);
    $labelAttributes = $attributes->only(['label:class']);
@endphp

<div class="{{ $wrapperAttributes->get('wrapper:class') }}">
    {{-- Label --}}
    @if ($label)
        <label
            for="{{ $id }}"
            class="block mb-2.5 text-sm font-medium text-heading {{ $labelAttributes->get('label:class') }}"
        >
            {{ $label }}
            @if ($required) <span class="text-red-400">*</span> @endif
        </label>
    @endif

    {{-- Input --}}
    <input 
        type="text" 
        id="{{ $id }}" 
        placeholder="{{ $placeholder }}" 
        @if($required) required @endif 
        @if($disabled) disabled @endif
        {{ $attributes->class([
            'bg-gray-100 border border-default-medium text-black text-sm rounded-base focus:ring-brand focus:border-brand block w-full ' . $sizeClasses . ' shadow-xs placeholder:text-body dark:placeholder:text-gray-800'
        ]) }}
    />
</div>