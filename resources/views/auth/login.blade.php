<x-app-layout>
    <div class="flex justify-center items-center min-h-screen text-white bg-neutral-950">
        <div class="w-full max-w-md">
            <div class="bg-neutral-200 p-8 rounded-lg shadow-xl">
                <h2 class="font-bold text-2xl text-white mb-4">Login</h2>
                <x-validation-errors class="mb-4" />
                @if (session('status'))
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="mb-4">
                        <x-label for="username" class="text-white" value="{{ __('username') }}" />
                        <x-input id="username" class="block mt-1 w-full bg-base-300 text-white" type="username"
                            name="username" :value="old('username')" required autofocus autocomplete="username" />
                    </div>
                    <div class="mb-4">
                        <x-label for="password" class="text-white" value="{{ __('Password') }}" />
                        <x-input id="password" class="block mt-1 w-full bg-base-300 text-white" type="password"
                            name="password" required autocomplete="current-password" />
                    </div>
                    <div class="block mb-4">
                        <label for="remember_me" class="flex items-center">
                            <x-checkbox id="remember_me" name="remember" />
                            <span class="ms-2 text-sm text-white">{{ __('Remember me') }}</span>
                        </label>
                    </div>
                    <div class="flex items-center justify-end">
                        @if (Route::has('password.request'))
                            <a class="underline text-sm text-white hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-base-200"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot your password?') }}
                            </a>
                        @endif
                        <x-button class="ms-4 bg-primary text-white">
                            {{ __('Log in') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
