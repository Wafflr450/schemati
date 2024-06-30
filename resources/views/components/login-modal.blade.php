<div x-data="{ open: false }" x-on:login-modal.window="open = true" x-on:keydown.escape.window="open = false"
    x-on:click.outside="open = false" x-show="open" class="fixed inset-0 z-50 overflow-y-auto" x-cloak>
    <div class="flex items-center justify-center min-h-screen">
        <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" x-on:click="open = false"></div>

        <div x-show="open" x-transition:enter="ease-out duration-300 z-100" x-transition:enter-start="opacity-0"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="w-full max-w-md p-8 mx-auto bg-neutral-900 rounded-lg shadow-xl z-100" x-on:click.stop>

            <div class="flex items-center justify-center mb-6">
                <x-application-mark class="w-auto h-16 mx-auto" :isDark="true" />
            </div>

            <h2 class="text-3xl font-bold text-white mb-6 text-center">Sign In</h2>

            <x-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 text-sm font-medium text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                <div class="mb-4">
                    <x-label for="username" class="text-white text-sm font-semibold mb-2"
                        value="{{ __('Username') }}" />
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <x-input id="username"
                            class="block w-full pl-10 pr-3 py-2 border border-neutral-700 rounded-md leading-5 bg-neutral-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm"
                            type="text" name="username" :value="old('username')" required autofocus autocomplete="username"
                            placeholder="Enter your username" />
                    </div>
                </div>
                <div class="mb-4">
                    <x-label for="password" class="text-white text-sm font-semibold mb-2"
                        value="{{ __('Password') }}" />
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        <x-input id="password"
                            class="block w-full pl-10 pr-3 py-2 border border-neutral-700 rounded-md leading-5 bg-neutral-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary sm:text-sm"
                            type="password" name="password" required autocomplete="current-password"
                            placeholder="Enter your password" />
                    </div>
                </div>
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember"
                            class="text-primary focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" />
                        <span class="ml-2 text-sm text-gray-400">{{ __('Remember me') }}</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-primary hover:text-primary-600 focus:outline-none focus:underline"
                            href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>
                <div>
                    <x-button
                        class="w-full justify-center bg-primary hover:bg-primary-600 focus:bg-primary-600 active:bg-primary-700 text-white">
                        {{ __('Sign In') }}
                    </x-button>
                </div>
            </form>
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-400">
                    {{ __("Don't have an account?") }}
                    <button
                        class="ml-1 font-medium text-primary hover:text-primary-600 focus:outline-none focus:underline"
                        x-on:click="$dispatch('register-modal'); open = false">
                        {{ __('Register') }}
                    </button>
                </p>
            </div>
        </div>
    </div>
</div>
