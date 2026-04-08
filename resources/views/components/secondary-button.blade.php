<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm transition ease-in-out duration-150 disabled:opacity-25', 'style' => 'background-color: #ffffff; border: 1px solid #cbd5e1;']) }}>
    {{ $slot }}
</button>
