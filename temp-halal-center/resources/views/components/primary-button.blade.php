<button {{ $attributes->merge(['type' => 'submit', 'class' => 'auth-primary-button']) }}>
    {{ $slot }}
</button>
