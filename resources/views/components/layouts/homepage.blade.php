@props(['title'])
@php
    // Ambil nama route saat ini.
    $current_route = collect(explode('.', request()->route()->getName()))->implode('.');
    $is_root_page = request()->routeIs('client.homepage.index');
@endphp
<x-layouts.app title="{{ $title ?? '' }}">
    <div class="flex flex-col min-h-screen">
        {{-- navbar --}}
        <div class="bg-base-200 sticky top-0 z-10 w-full">
            <div class="container mx-auto">
                <div class="navbar px-8">
                    <a class="flex flex-1" href="/">
                        <x-ui.image src="{{ route('storage.public.get', ['path' => config('site.company_icon')]) }}"
                            class="max-w-24 w-full" />
                    </a>
                    {{-- Mobile dropdown --}}
                    <div class="flex flex-none lg:hidden">
                        <div class="drawer">
                            <input id="my-drawer-1" type="checkbox" class="drawer-toggle" />
                            <div class="drawer-content">
                                {{-- Button dropdown --}}
                                <label for="my-drawer-1" class="btn drawer-button">
                                    <x-lucide-menu class="w-4" />
                                </label>
                            </div>
                            <div class="drawer-side">
                                <label for="my-drawer-1" aria-label="close sidebar" class="drawer-overlay"></label>
                                <ul class="dropdown-content menu bg-base-200 min-h-full w-64 shadow gap-2">
                                    <li class="menu-title text-neutral text-xl">Menu</li>
                                    <li>
                                        <a href="{{ $is_root_page ? '#' : route('client.homepage.index') }}">
                                            Beranda
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ $is_root_page ? '#about' : route('client.homepage.index') . '#about' }}">
                                            Tentang kami
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ $is_root_page ? '#services' : route('client.homepage.index') . '#services' }}">
                                            Layanan kami
                                        </a>
                                    </li>
                                    <li>
                                        <a
                                            href="{{ $is_root_page ? '#contact' : route('client.homepage.index') . '#contact' }}">
                                            Kontak kami
                                        </a>
                                    </li>
                                    @if (auth()->user()?->role->value === 'tamu' || auth()->user()?->role->value === 'anggota')
                                        <li>
                                            <a href="{{ route('client.dashboard.index') }}" class="btn btn-accent">
                                                <x-lucide-layout-grid class="w-4" />
                                                Dashboard
                                            </a>
                                        </li>
                                    @else
                                        <li>
                                            <a href="{{ route('client.login') }}" class="btn btn-accent">
                                                <x-lucide-waypoints class="w-4" />
                                                Gabung sekarang
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{-- Menu desktop --}}
                    <div class="lg:flex hidden flex-none text-base-content">
                        <ul class="menu menu-horizontal items-center gap-4 rounded-box">
                            <li>
                                <a href="{{ $is_root_page ? '#' : route('client.homepage.index') }}">
                                    Beranda
                                </a>
                            </li>
                            <li>
                                <a href="{{ $is_root_page ? '#about' : route('client.homepage.index') . '#about' }}">
                                    Tentang kami
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ $is_root_page ? '#services' : route('client.homepage.index') . '#services' }}">
                                    Layanan kami
                                </a>
                            </li>
                            <li>
                                <a
                                    href="{{ $is_root_page ? '#contact' : route('client.homepage.index') . '#contact' }}">
                                    Kontak kami
                                </a>
                            </li>
                            @if (auth()->user()?->role->value === 'tamu' || auth()->user()?->role->value === 'anggota')
                                <li>
                                    <a href="{{ route('client.dashboard.index') }}" class="btn btn-accent">
                                        <x-lucide-layout-grid class="w-4" />
                                        Dashboard
                                    </a>
                                </li>
                            @else
                                <li>
                                    <a href="{{ route('client.login') }}" class="btn btn-accent">
                                        <x-lucide-waypoints class="w-4" />
                                        Gabung sekarang
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        {{-- Content --}}
        <div class="flex flex-col gap-4">

            {{ $slot }}
        </div>
        {{-- Footer --}}
        <footer class="footer footer-vertical md:footer-horizontal bg-base-200 text-base-content p-8 mt-auto">
            <aside class="max-w-xs">
                <x-ui.image src="{{ route('storage.public.get', ['path' => config('site.company_icon')]) }}"
                    class="max-w-32 w-full" />
                <p class="text-base-content/75">
                    Jl. R. Aria Natamanggala KM 10 Desa Kademangan Kecamatan Mande Kabupaten Cianjur.
                </p>
            </aside>
            <nav>
                <h6 class="footer-title">Layanan kami</h6>
                <a class="link link-hover">Pinjaman rakyat</a>
            </nav>
            <nav>
                <h6 class="footer-title">Menu utama</h6>
                <a class="link link-hover"
                    href="{{ $is_root_page ? '#' : route('client.homepage.index') . '#' }}">Beranda</a>
                <a class="link link-hover"
                    href="{{ $is_root_page ? '#about' : route('client.homepage.index') . '#about' }}">Tentang
                    kami</a>
                <a class="link link-hover"
                    href="{{ $is_root_page ? '#services' : route('client.homepage.index') . '#services' }}">Layanan
                    kami</a>
                <a class="link link-hover"
                    href="{{ $is_root_page ? '#contact' : route('client.homepage.index') . '#contact' }}">Kontak
                    kami</a>
            </nav>
            <nav>
                <h6 class="footer-title">Sosial media</h6>
                <a class="link link-hover" href="{{ route('client.homepage.index') }}">Instagram</a>
                <a class="link link-hover" href="{{ route('client.homepage.index') }}">Youtube</a>
                <a class="link link-hover" href="{{ route('client.homepage.index') }}">Facebook</a>
                <a class="link link-hover" href="{{ route('client.homepage.index') }}">Tiktok</a>
            </nav>
            <nav>
                <h6 class="footer-title">Lainnya</h6>
                <a class="link link-hover" href="{{ route('client.homepage.syarat-dan-ketentuan') }}">Syarat &
                    ketentuan</a>
            </nav>
        </footer>
    </div>
</x-layouts.app>
