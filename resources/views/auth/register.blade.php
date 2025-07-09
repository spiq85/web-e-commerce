{{-- resources/views/auth/register.blade.php --}}

<x-guest-layout>
    <div class="mb-4 text-center text-rubylux-light text-lg font-semibold">
        Register Dulu yuk!
        <p class="text-sm text-gray-400">Gabung dengan RubyLux untuk pengalaman belanja yang mewah.</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="font-bold">Oops! Ada beberapa masalah input:</span>
            <ul class="mt-3 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <x-input-label for="username" value="{{ __('Username') }}" class="text-rubylux-light" />
            <x-text-input id="username" class="block mt-1 w-full form-input" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-4">
            <x-input-label for="email" value="{{ __('Email') }}" class="text-rubylux-light" />
            <x-text-input id="email" class="block mt-1 w-full form-input" type="email" name="email" :value="old('email')" required autocomplete="email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" value="{{ __('Password') }}" class="text-rubylux-light" />
            <x-text-input id="password" class="block mt-1 w-full form-input" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-4">
            <x-input-label for="password_confirmation" value="{{ __('Konfirmasi Password') }}" class="text-rubylux-light" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full form-input" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-400" />
        </div>

        <div class="mt-4">
            <x-input-label for="role" value="{{ __('Role') }}" class="text-rubylux-light" />
            <select id="role" name="role" class="block mt-1 w-full form-select rounded-md shadow-sm">
                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2 text-red-400" />
        </div> 

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-rubylux-light hover:text-rubylux-ruby rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rubylux-ruby" href="{{ route('login') }}">
                {{ __('Sudah Punya Akun?') }}
            </a>

            <x-primary-button class="ml-4 bg-rubylux-ruby hover:bg-rubylux-ruby-dark text-rubylux-light">
                {{ __('Daftar') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>