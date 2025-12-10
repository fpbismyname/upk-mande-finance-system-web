@props(['title'])
@php
    // Ambil nama route saat ini.
    $current_route = collect(explode('.', request()->route()->getName()))
        ->shift(2)
        ->implode('.');

    // Ambil navbar menu
    $navbar_menu = config('client_navbar');
@endphp
<x-layouts.app title="{{ $title ?? '' }}">
    <div class="flex flex-col min-h-screen">
        {{-- Alert data user --}}
        @if (!auth()->user()->is_data_user_lengkap && auth()->user()->pengajuan_keanggotaan_disetujui()->exists())
            <div class="flex flex-row p-2 bg-primary text-white gap-2 items-center transition-all">
                <x-lucide-circle-alert class="min-w-4 max-w-4" />
                <small>Jangan lupa untuk melengkapi data anda agar pengajuan pinjaman dapat
                    disetujui.</small>
                <x-lucide-x class="min-w-4 max-w-4 cursor-pointer ms-auto" onclick="this.parentElement.hidden = true" />
            </div>
        @endif
        {{-- navbar --}}
        <div class="bg-base-200">
            <div class="container mx-auto">
                <div class="navbar px-8">
                    <div class="flex flex-1">
                        <a class="flex" href="{{ route('client.dashboard.index') }}">
                            <x-ui.image src="{{ route('storage.public.get', ['path' => config('site.company_icon')]) }}"
                                class="max-w-16 w-full" />
                        </a>
                    </div>
                    {{-- Mobile dropdown --}}
                    <div class="flex flex-none md:hidden">
                        <div class="drawer">
                            <input id="my-drawer-1" type="checkbox" class="drawer-toggle" />
                            <div class="drawer-content">
                                {{-- Button dropdown --}}
                                <label for="my-drawer-1" class="btn btn-accent drawer-button">
                                    <x-lucide-menu class="w-4" />
                                </label>
                            </div>
                            <div class="drawer-side">
                                <label for="my-drawer-1" aria-label="close sidebar" class="drawer-overlay"></label>
                                <ul class="menu gap-2 bg-base-100 min-h-full w-64">
                                    <li class="menu-title text-primary text-xl">Menu</li>
                                    @foreach ($navbar_menu as $menu)
                                        @switch($menu['type'])
                                            @case('menu')
                                                <li>
                                                    <a href="{{ route($menu['route_name']) }}"
                                                        class="{{ Str::contains($menu['route_name'], $current_route) ? 'menu-active' : '' }}">
                                                        <x-dynamic-component :component="'lucide-' . $menu['icon']" class="w-4" />
                                                        {{ $menu['title'] }}
                                                    </a>
                                                </li>
                                            @break
                                        @endswitch
                                    @endforeach
                                    @if (auth()->user()->pengajuan_keanggotaan_disetujui()->exists())
                                        <li>
                                            <a href="{{ route('client.settings.index') }}"
                                                class="{{ Str::contains('client.settings.index', $current_route) ? 'menu-active' : '' }}">
                                                <x-lucide-settings class="w-4" />
                                                Pengaturan
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <button class="btn btn-soft btn-error justify-start"
                                            onclick="window.submit_form('logout-user-mobile')">
                                            <x-lucide-log-out class="w-4" />
                                            <span>Logout</span>
                                        </button>
                                        <form id="logout-user-mobile" action="{{ route('client.logout') }}"
                                            method="post" class="w-full hidden">
                                            @csrf
                                            @method('post')
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- Menu desktop --}}
                    <div class="md:flex hidden flex-none text-base-content">
                        <ul class="menu menu-horizontal items-center gap-4">
                            @foreach ($navbar_menu as $menu)
                                @switch($menu['type'])
                                    @case('menu')
                                        <li>
                                            <a href="{{ route($menu['route_name']) }}"
                                                class="{{ Str::contains($menu['route_name'], $current_route) ? 'menu-active' : '' }}">
                                                <x-dynamic-component :component="'lucide-' . $menu['icon']" class="w-4" />
                                                <span class="hidden lg:inline peer-[]:">
                                                    {{ $menu['title'] }}
                                                </span>
                                            </a>
                                        </li>
                                    @break
                                @endswitch
                            @endforeach
                            <div class="dropdown dropdown-end">
                                <div tabindex="0" role="button" class="btn btn-circle btn-accent">
                                    <x-lucide-user class="w-4" />
                                </div>
                                <ul class="dropdown-content menu w-64 bg-base-100 shadow rounded-box">
                                    <li class="menu-title">{{ auth()->user()->name }}</li>
                                    @if (auth()->user()->pengajuan_keanggotaan_disetujui()->exists())
                                        <li>
                                            <a href="{{ route('client.settings.index') }}"
                                                class="{{ Str::contains('client.settings.index', $current_route) ? 'menu-active' : '' }}">
                                                <x-lucide-settings class="w-4" />
                                                Pengaturan
                                            </a>
                                        </li>
                                    @endif
                                    <li>
                                        <button class="text-error justify-start"
                                            onclick="window.submit_form('logout-user')">
                                            <x-lucide-log-out class="w-4" />
                                            <span>Logout</span>
                                        </button>
                                        <form id="logout-user" action="{{ route('client.logout') }}" method="post"
                                            class="w-full hidden">
                                            @csrf
                                            @method('post')
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="container mx-auto p-4">
            <div class="flex flex-col gap-6">
                {{ $slot }}
            </div>
        </div>
    </div>
</x-layouts.app>
