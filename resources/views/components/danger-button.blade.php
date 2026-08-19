<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-ripple inline-flex items-center gap-2 px-4 py-2 bg-neutral-800 border border-neutral-800 rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-black focus:outline-none focus:ring-2 focus:ring-neutral-500 focus:ring-offset-2 disabled:opacity-40 transition-colors duration-150']) }}>
    {{ $slot }}
</button>
