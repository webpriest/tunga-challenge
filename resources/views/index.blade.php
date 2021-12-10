<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
           <h1 class="text-3xl font-bold">{{ __('Tunga Code Challenge') }}</h1>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        @include('layouts.partials._notification')

        <form method="POST" action="{{ route('profile.store') }}" enctype="multipart/form-data">
            @csrf

            <h2 class="font-bold text-lg">{{ __('Upload JSON file') }}</h2>
            <div>
                <x-input id="email" class="block mt-1 w-full" type="file" name="json_file" required/>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3">
                    {{ __('Import JSON') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
