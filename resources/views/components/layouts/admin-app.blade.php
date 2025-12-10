@props(['title', 'gap' => 4])
@php
    // Ambil nama route saat ini.
    $current_route = collect(explode('.', request()->route()->getName()))
        ->shift(2)
        ->implode('.');
    // Filter menu berdasarkan permission role.
    $sidebar_menu = config('admin_sidebar')->filter(
        fn($item) => in_array(auth()->user()?->role->value, $item['roles']),
    );
@endphp
<x-layouts.app :title="$title">
    <div class="drawer md:drawer-open">
        <input id="admin-sidebar" type="checkbox" class="drawer-toggle" />

        <!-- Contents -->
        <div class="drawer-content flex flex-col">
            {{-- Alert content --}}
            @can('has-bank-account-number')
                @empty(auth()->user()->pengajuan_keanggotaan_disetujui()->first()->nomor_rekening)
                    <div class="flex flex-row gap-2 text-white items-center p-2 bg-primary">
                        <x-lucide-circle-alert class="min-w-4 max-w-4" />
                        <small>Jangan lupa untuk melengkapi nomor rekening untuk keperluan pembayaran pinjaman kelompok.</small>
                        <x-lucide-x class="min-w-4 max-w-4 cursor-pointer ms-auto" onclick="this.parentElement.hidden = true" />
                    </div>
                @endempty
            @endcan
            <div class="navbar bg-base-200 gap-4 md:hidden">
                <label for="admin-sidebar" class="btn drawer-button md:hidden">
                    <x-lucide-menu class="w-4" />
                </label>
                <span>
                    {{ $app['name'] }}
                </span>
            </div>
            <div class="flex flex-col min-h-screen w-full py-8 px-4 md:px-8 gap-{{ $gap }}">
                {{-- Header content --}}
                <div class="flex flex-row w-full items-center flex-wrap gap-4 justify-between">
                    <div class="flex flex-col w-full md:w-fit">

                        <div class="flex flex-row">
                            <h2>{{ $title ?? '' }}</h2>
                        </div>
                        @if (!empty($below_title))
                            {{ $below_title }}
                        @endif
                    </div>
                    @isset($right_item)
                        <div class="flex gap-4">
                            {{ $right_item ?? null }}
                        </div>
                    @endisset
                </div>
                {{ $slot }}
            </div>
        </div>
        <div class="drawer-side">
            <label for="admin-sidebar" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-base-200 px-4 min-h-full w-80">

                {{-- Header sidebar --}}
                <div class="flex flex-col gap-4 py-12 px-4 items-center">
                    <x-ui.image :src="route('storage.public.get', ['path' => config('site.company_icon')])" class="max-w-32 mx-auto" />
                    <span class="badge badge-secondary">
                        {{ auth()->user()?->role->label() }}
                    </span>
                </div>

                <!-- Menu sidebar -->
                @foreach ($sidebar_menu as $item)
                    @switch($item['type'])
                        @case('menu')
                            <li>
                                <a href="{{ route($item['route_name' ?? '']) }}"
                                    class="@if (Str::contains($item['route_name'], $current_route)) menu-active bg-neutral text-base-100 @endif">
                                    <x-dynamic-component :component="'lucide-' . $item['icon']" class="w-4" />
                                    <span>{{ $item['title'] }}</span>
                                </a>
                            </li>
                        @break

                        @case('menu-title')
                            <li class="menu-title">{{ $item['title'] }}</li>
                        @break
                    @endswitch
                @endforeach

                {{-- Accounts --}}
                <div class="sticky bottom-4 w-full z-10 mt-auto">
                    <div class="dropdown dropdown-top w-full">
                        <div tabindex="0" class="btn btn-primary w-full justify-between">
                            <div class="flex flex-row items-center gap-2">
                                <x-lucide-user class="w-4" />
                                <span>{{ auth()->user()?->name }}</span>
                            </div>
                            <x-lucide-ellipsis-vertical class="w-4" />
                        </div>
                        <ul class="dropdown-content menu w-full bg-base-100 rounded-box">
                            <li>
                                <a href="{{ route('admin.settings.index') }}">
                                    <x-lucide-settings class="w-4" />
                                    <span>Pengaturan</span>
                                </a>
                            </li>
                            <li>
                                <button class="flex gap-2 w-full text-error"
                                    onclick="window.submit_form('logout-form')">
                                    <x-lucide-log-out class="w-4" />
                                    <span>Logout</span>
                                </button>
                                <form id="logout-form" method="POST" action="{{ route('admin.logout') }}"
                                    class="w-full hidden">
                                    @method('POST')
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </ul>
        </div>
    </div>
</x-layouts.app>
