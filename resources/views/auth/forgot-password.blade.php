<x-guest-layout>
    <!-- Logo Flora -->
    <div class="flex justify-center mb-6">
        <img src="{{ asset('images/flora_logo.jpg') }}" alt="Flora Logo" class="h-full w-full">
    </div>

    <!-- Text "Silahkan Masuk" -->
    <div class="flex items-center my-4">
        <hr class="flex-grow border-t border-gray-300 dark:border-gray-600">
        <span class="px-3 text-sm text-gray-500 dark:text-gray-400">
            Reset Password
        </span>
        <hr class="flex-grow border-t border-gray-300 dark:border-gray-600">
    </div>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Forgot your password? No problem.') }}
        <br>
        {{ __('Just let us know your email address...') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="off" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
