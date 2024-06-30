<div x-data="{ open: false }" x-on:register-modal.window="open = true" x-on:keydown.escape.window="open = false"
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
            <h2 class="font-bold text-3xl text-white mb-6 text-center">
                Register
            </h2>
            <p class="text-white mb-6 text-center">
                To register, connect to the auth server:
            </p>
            <div class="bg-neutral-800 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-white">IP:</span>
                        <span class="text-white font-semibold">auth.schemat.io</span>
                    </div>
                    <button class="text-primary hover:text-primary-600 focus:outline-none focus:underline"
                        onclick="copyToClipboard('auth.schemat.io')">
                        <i class="far fa-copy mr-1"></i>Copy
                    </button>
                </div>
            </div>
            <p class="text-white mb-4 text-center">
                Once connected, type the following command:
            </p>
            <div class="bg-neutral-800 rounded-lg p-4 mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="text-white font-semibold">/password &lt;password&gt;</span>
                    </div>
                    <button class="text-primary hover:text-primary-600 focus:outline-none focus:underline"
                        onclick="copyToClipboard('/password ')">
                        <i class="far fa-copy mr-1"></i>Copy
                    </button>
                </div>
            </div>
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-400">
                    {{ __('Already have an account?') }}
                    <button
                        class="ml-1 font-medium text-primary hover:text-primary-600 focus:outline-none focus:underline"
                        x-on:click="$dispatch('login-modal'); open = false">
                        {{ __('Sign In') }}
                    </button>
                </p>
            </div>
        </div>
    </div>
</div>
