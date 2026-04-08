<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs text-white uppercase tracking-widest transition ease-in-out duration-150', 'style' => 'background-color: #0f172a; border: 1px solid #0f172a;']) }}>
    {{ $slot }}
</button>
