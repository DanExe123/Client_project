<div>
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
          
    </header>
    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            <div class="text-[13px] leading-[20px] flex-1 p-6 pb-12 lg:p-20 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-es-lg rounded-ee-lg lg:rounded-ss-lg lg:rounded-ee-none">
                
<div class="flex flex-col gap-6">
<x-auth-header :title="__('Log in to your account')" :description="__('Enter your email and password below to log in')" />

<!-- Session Status -->
<x-auth-session-status class="text-center" :status="session('status')" />

<form wire:submit.prevent="login" class="flex flex-col gap-6">
    <!-- Email Address -->
    <flux:input
        wire:model="email"
        :label="__('Email address')"
        type="email"
        required
        autofocus
        autocomplete="email"
        placeholder="email@example.com"
    />

    <!-- Password -->
    <div class="relative">
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="current-password"
            :placeholder="__('Password')"
        />

    </div>

    <!-- Remember Me -->
    <flux:checkbox wire:model="remember" :label="__('Remember me')" />

    <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">{{ __('Log in') }}</flux:button>
              </div>
</form>

@if (Route::has('register'))
    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Don\'t have an account?') }}
        <flux:link :href="route('register')" wire:navigate>{{ __('Sign up') }}</flux:link>
    </div>
@endif
</div>

            </div>
            <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ms-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg! aspect-[335/376] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                <img src="{{ asset('logo/3275434.jpg') }}" alt="Dnexus" class="w-full h-full">

             
                <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-e-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</div>
