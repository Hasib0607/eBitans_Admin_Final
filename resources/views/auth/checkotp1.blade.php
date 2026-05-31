<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('Code send your mobile') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('match.code') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Code')" />

                <x-input id="code" class="block mt-1 w-full" type="text" name="code"  required autofocus />
            </div>
            

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Submit OTP') }}
                </x-button>
            </div>
            <div class="flex items-center justify-start mt-4">
                <a href="{{route('getcode')}}">
                    {{ __('Request for New One') }}
                </a>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
