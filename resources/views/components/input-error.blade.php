@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-neutral-900 font-medium space-y-1']) }}>
        @foreach ((array) $messages as $message)
            <li>&bull; {{ $message }}</li>
        @endforeach
    </ul>
@endif
