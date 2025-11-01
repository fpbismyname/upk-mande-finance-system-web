@props(['datas' => []])
<section id="#" class="fixed w-full top-0 z-10 bg-base-100">
    <div class="container mx-auto">
        <div class="navbar px-6 items-center">
            <div class="navbar-start">
                <a href="{{ route('client.home') }}">
                    <x-ui.image :src="asset('nav_icon.ico')" class="w-24 h-auto"/>
                </a>
            </div>
            <div class="hidden md:flex md:flex-none md:ms-auto">
                <ul class="menu menu-horizontal px-1 items-center justify-end gap-4">
                    <li><a>Beranda</a></li>
                    <li><a>Profil</a></li>
                    <li><a>Tentang kami</a></li>
                    <li>
                        <a href="" class="btn btn-primary btn-soft gap-2">
                            <x-lucide-waypoints class="w-4" />
                            {{ $datas['cta'] }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="navbar-end flex md:hidden">
                <div class="drawer drawer-end">
                    <input id="home-sidebar" type="checkbox" class="drawer-toggle" />
                    <div class="drawer-content ms-auto">
                        <!-- Page content here -->
                        <label for="home-sidebar" class="drawer-button btn btn-ghost">
                            <x-lucide-menu class="w-4" />
                        </label>
                    </div>
                    <div class="drawer-side">
                        <label for="home-sidebar" aria-label="close sidebar" class="drawer-overlay"></label>
                        <ul class="menu bg-base-100 min-h-full w-80 p-4 text-base">
                            <!-- Sidebar content here -->
                            <div class="flex justify-between p-2 items-center">
                                <h1>{{ $app['name'] }}</h1>
                                <label for="home-sidebar" class="drawer-button btn">
                                    <x-lucide-x class="w-4" />
                                </label>
                            </div>
                            <li><a>Beranda</a></li>
                            <li><a>Profil</a></li>
                            <li><a>Tentang kami</a></li>
                            <li>
                                <a href="{{ route('client.home') }}" class="text-primary">
                                    <x-lucide-waypoints class="w-4" />
                                    Gabung sekarang
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
