{{-- resources/views/auth/login.blade.php --}}

<x-guest-layout>
    <div class="mb-4 text-center text-rubylux-light text-lg font-semibold">
        Selamat Datang di RubyLux!
        <p class="text-sm text-gray-400">Silakan login untuk pengalaman berbelanja terbaik.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" value="{{ __('Email') }}" class="text-rubylux-light" />
            <x-text-input id="email" class="block mt-1 w-full form-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password') }}" class="text-rubylux-light" />
            <x-text-input id="password" class="block mt-1 w-full form-input" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-rubylux-ruby shadow-sm focus:ring-rubylux-ruby" name="remember">
                <span class="ml-2 text-sm text-rubylux-light">{{ __('Ingat Saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-rubylux-light hover:text-rubylux-ruby rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rubylux-ruby" href="{{ route('password.request') }}">
                    {{ __('Lupa Password?') }}
                </a>
            @endif

            <x-primary-button class="ml-3 bg-rubylux-ruby hover:bg-rubylux-ruby-dark text-rubylux-light">
                {{ __('Login') }}
            </x-primary-button>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-rubylux-light">
                Belum Punya Akun?
                <a href="{{ route('register') }}" class="underline text-rubylux-ruby hover:text-rubylux-ruby-dark">Daftar Sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>