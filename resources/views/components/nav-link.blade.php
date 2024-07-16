@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'inline-flex items-center px-1 border-b-2 border-primary text-sm font-medium text-gray-900 focus:outline-none focus:border-gray-500 transition duration-150 ease-in-out text-decoration-none'
            : 'inline-flex items-center px-1 border-b-2 border-transparent text-sm font-medium  fw-light text-gray-600 hover:text-gray-900 hover:border-gray-500 focus:outline-none focus:text-gray-300 focus:border-warning-emphasis transition duration-150 ease-in-out text-decoration-none';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
