@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'list-group', 'style' => 'font-size:12px;']) }}>
        @foreach ((array) $messages as $message)
            <li class="list-group-item list-group-item-danger py-1">{{ $message }}</li>
        @endforeach
    </ul>
@endif
