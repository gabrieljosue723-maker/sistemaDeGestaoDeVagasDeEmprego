<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-ripple btn-ripple--light inline-flex items-center gap-2 px-4 py-2 bg-white border border-neutral-300 rounded-lg font-semibold text-xs text-neutral-700 uppercase tracking-widest shadow-sm hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-neutral-400 focus:ring-offset-2 disabled:opacity-40 transition-colors duration-150']) }}>
    {{ $slot }}
</button>
