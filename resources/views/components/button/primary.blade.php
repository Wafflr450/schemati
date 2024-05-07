<button
    {{ $attributes->merge(['type' => 'button', 'class' => 'text-white bg-gradient-to-r from-primary-600 to-secondary-500 hover:bg-gradient-to-l focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 font-medium rounded-lg text-sm px-5 py-2.5 text-center']) }}>
    {{ $slot }}
</button>
