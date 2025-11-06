@props(['title', 'gap' => 4, 'breadcrumbs' => []])
@php
    $current_route = collect(explode('.', request()->route()->getName()))
        ->shift(2)
        ->implode('.');
@endphp
<x-layouts.app :title="$title">
    <div class="drawer md:drawer-open">
        <input id="admin-sidebar" type="checkbox" class="drawer-toggle" />

        <!-- Contents -->
        <div class="drawer-content flex flex-col">
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
                        @if (!empty($breadcrumbs))
                            <div class="breadcrumbs text-sm">
                                <ul>
                                    @foreach ($breadcrumbs as $href => $label)
                                        <li>
                                            @if ($loop->last)
                                                {{ Str::ucfirst($label) }}
                                            @else
                                                <a href="{{ $href }}">
                                                    {{ Str::ucfirst($label) }}
                                                </a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="flex flex-row">
                            <h2>{{ $title ?? '' }}</h2>
                        </div>
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
                    <x-ui.image src="{{ asset('nav_icon.ico') }}" class="w-1/2" />
                    <span class="badge badge-secondary">
                        {{ auth()->user()->formatted_role }}
                    </span>
                </div>

                <!-- Menu sidebar -->
                @foreach (App\Constants\AdminSidebar::list_menu() as $item)
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
                                <span>{{ auth()->user()->name }}</span>
                            </div>
                            <x-lucide-ellipsis-vertical class="w-4" />
                        </div>
                        <ul class="dropdown-content menu w-full bg-base-100 rounded-box">
                            <li>
                                <a href="{{ route('admin.settings.view') }}">
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
                                <form id="logout-form" method="POST" action="{{ route('admin.logout.submit') }}"
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
