<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
    @wireUiStyles
    @livewireStyles
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:header container class="border-b bg-[#00e6e6]  dark:border-zinc-700 dark:bg-zinc-900 py-4">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <a href="{{ route('dashboard') }}" class="ms-2 me-5 flex items-center space-x-2 rtl:space-x-reverse lg:ms-0"
            wire:navigate>
            <x-app-logo />
        </a>


        <flux:dropdown position="bottom" align="end" class="pl-5">
            <flux:profile name="MasterFiles" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('customer-master') }}" icon="user">
                    Customer Master
                </flux:navmenu.item>

                <flux:navmenu.item wire:navigate href="{{ route('product-master') }}" icon="building-storefront">
                    Product Master
                </flux:navmenu.item>

                <flux:navmenu.item wire:navigate href="{{ route('supplier-master') }}" icon="credit-card">
                    Supplier Master
                </flux:navmenu.item>

                <flux:navmenu.item wire:navigate href="{{ route('user-list') }}" icon="arrow-right-start-on-rectangle">
                    User List
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>

        <flux:dropdown position="bottom" align="end">
            <flux:profile name="Purchasing" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('po-to-supplier') }}" icon="user">P.O to Supplier
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('customer-po') }}" icon="credit-card">Customer P.O
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>

        <flux:dropdown position="bottom" align="end">
            <flux:profile name="Inventory" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('recieving') }}" icon="user">Recieving
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('return-by-customer') }}" icon="building-storefront">
                    Returned by Customer</flux:navmenu.item>
                {{-- <flux:navmenu.item href="#" icon="credit-card">Damages</flux:navmenu.item>--}}
                <flux:navmenu.item wire:navigate href="{{ route('return-by-supplier') }}"
                    icon="arrow-right-start-on-rectangle">Returned To Supplier</flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('stockcard') }}" icon="trash" variant="danger">Stock
                    Card</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>


        <flux:dropdown position="bottom" align="end">
            <flux:profile name="Recievables" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('sales-releasing') }}" icon="user">Sales Releasing
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('unservered-lacking') }}" icon="building-storefront">
                    Unserved lacking letter</flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('account-recievables') }}" icon="credit-card">Account
                    receivables</flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('credit-debit') }}"
                    icon="arrow-right-start-on-rectangle">Credit/Debit</flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('payment-application') }}" icon="trash"
                    variant="danger">Payment Application</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>


        <flux:dropdown position="bottom" align="end">
            <flux:profile name="Payable" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('account-payable') }}" icon="user">Account payable
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('payable-ledger') }}" icon="building-storefront">Payable
                    Ledger</flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>


        <flux:dropdown position="bottom" align="end">
            <flux:profile name="General Ledger" />
            <flux:navmenu>
                <flux:navmenu.item wire:navigate href="{{ route('sales-summary') }}" icon="user">Sales Summary
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('sales-book') }}" icon="building-storefront">Sales Book
                </flux:navmenu.item>
                <flux:navmenu.item wire:navigate href="{{ route('cash-flow') }}" icon="credit-card">Cash Flow
                </flux:navmenu.item>
            </flux:navmenu>
        </flux:dropdown>

        <flux:spacer />



        <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
        </flux:navbar>

        <!-- Desktop User Menu -->
        <flux:dropdown position="top" align="end">
            <flux:profile class="cursor-pointer" :initials="auth()->user()->initials()" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                <span
                                    class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                    {{ auth()->user()->initials() }}
                                </span>
                            </span>

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.radio.group>
                    <div class="flex justify-start ml-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 -h-5 text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.25 9.75v-4.5m0 4.5h4.5m-4.5 0 6-6m-3 18c-8.284 0-15-6.716-15-15V4.5A2.25 2.25 0 0 1 4.5 2.25h1.372c.516 0 .966.351 1.091.852l1.106 4.423c.11.44-.054.902-.417 1.173l-1.293.97a1.062 1.062 0 0 0-.38 1.21 12.035 12.035 0 0 0 7.143 7.143c.441.162.928-.004 1.21-.38l.97-1.293a1.125 1.125 0 0 1 1.173-.417l4.423 1.106c.5.125.852.575.852 1.091V19.5a2.25 2.25 0 0 1-2.25 2.25h-2.25Z" />
                        </svg>

                        <flux:menu.item :href="route('contact-developer')" wire:navigate>{{ __('Contact Developers') }}
                        </flux:menu.item>
                    </div>
                </flux:menu.radio.group>


                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        {{ __('Log Out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Mobile Menu -->
    <flux:sidebar stashable sticky
        class="lg:hidden border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="ms-1 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist variant="outline">
            <flux:navlist.group :heading="__('Platform')">
                <flux:navlist.item icon="layout-grid" :href="route('dashboard')"
                    :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist variant="outline">
            <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:navlist.item>

            <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
            </flux:navlist.item>
        </flux:navlist>
    </flux:sidebar>

    {{ $slot }}
    @livewireScripts
    @fluxScripts
    <wireui:scripts />
    <script src="//unpkg.com/alpinejs" defer></script>

</body>

</html>