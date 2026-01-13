<tr {{ $attributes->merge([
    'class' => "odd:bg-white even:bg-gray-100 dark:odd:bg-gray-600 dark:even:bg-gray-700 border-b border-default",
]) }}>

    {{ $slot }}
</tr>