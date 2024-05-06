<?php

use function Livewire\Volt\{mount, state, computed};
use App\Models\User;
use App\Models\Schematic;
use function Laravel\Folio\name;
use Illuminate\Support\Facades\Cache;

name('set-password');
state(['session'])->url();

state(['uuid', 'password', 'password_confirmation']);

mount(function () {
    $sessionUUID = Cache::get('password-set-session:' . $this->session);
    if (!$sessionUUID) {
        return redirect()->route('login');
    }
    $this->uuid = $sessionUUID;
});

$setPassword = function () {
    $this->validate([
        'password' => 'required|min:8|confirmed',
    ]);
    $user = User::firstOrCreate([
        'uuid' => $this->uuid,
    ]);
    $user->password = Hash::make($this->password);
    $user->save();
    Cache::forget('password-set-session:' . $this->session);
    return redirect()->route('login');
};
?>

<x-app-layout>
    @volt
        <div>
            <div class="flex justify-center items-center min-h-screen text-white bg-base-100">
                <div class="w-full max-w-md">
                    <div class="bg-base-200 p-8 rounded-lg shadow-xl">
                        <h2 class="font-bold text-2xl text-white mb-4">Set Password</h2>
                        <x-validation-errors class="mb-4" />
                        <form wire:submit.prevent="setPassword">
                            <div class="mb-4">
                                <x-label for="password" class="text-white" value="{{ __('Password') }}" />
                                <x-input id="password" class="block mt-1 w-full bg-base-300 text-white" type="password"
                                    name="password" wire:model="password" required autocomplete="new-password" />
                            </div>
                            <div class="mb-4">
                                <x-label for="password_confirmation" class="text-white"
                                    value="{{ __('Confirm Password') }}" />
                                <x-input id="password_confirmation" class="block mt-1 w-full bg-base-300 text-white"
                                    type="password" name="password_confirmation" wire:model="password_confirmation" required
                                    autocomplete="new-password" />
                            </div>
                            <div class="flex items-center justify-end space-x-4">
                                <x-button class="ms-4 bg-primary text-white">
                                    {{ __('Set Password') }}
                                </x-button>
                                <a href="{{ route('login') }}"
                                    class="pl-4 underline text-sm text-white hover:text-primary rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-offset-base-200">
                                    {{ __('Cancel') }}
                                </a>
                            </div>
                        </form>
                    </div>
                @endvolt
</x-app-layout>
