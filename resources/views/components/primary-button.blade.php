<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[#5045EB] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[#3c37d2] focus:bg-[#3c37d2] active:bg-[#2f2ab8] focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
