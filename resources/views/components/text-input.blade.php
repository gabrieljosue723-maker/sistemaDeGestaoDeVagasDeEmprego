@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-neutral-300 focus:border-neutral-600 focus:ring-neutral-600 rounded-md shadow-sm']) }}>
