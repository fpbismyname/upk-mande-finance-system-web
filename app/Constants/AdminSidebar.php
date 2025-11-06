<?php

namespace App\Constants;

class AdminSidebar
{
    public static function list_menu()
    {
        return [
            [
                'title' => 'Menu utama',
                'type' => 'menu-title',
                'roles' => ['admin']
            ],
            [
                'title' => 'Dashboard',
                'icon' => 'home',
                'type' => 'menu',
                'route_name' => 'admin.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Manajemen Pendanaan',
                'type' => 'menu-title',
                'roles' => ['admin']
            ],
            [
                'title' => 'Saldo Pendanaan',
                'icon' => 'wallet',
                'type' => 'menu',
                'route_name' => 'admin.pendanaan.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Manajemen Akun',
                'type' => 'menu-title',
                'roles' => ['admin']
            ],
            [
                'title' => 'Akun Pengguna',
                'icon' => 'circle-user',
                'type' => 'menu',
                'route_name' => 'admin.users.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Kelompok Upk',
                'icon' => 'users-round',
                'type' => 'menu',
                'route_name' => 'admin.kelompok.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Manajemen Pinjaman',
                'type' => 'menu-title',
                'roles' => ['admin']
            ],
            [
                'title' => 'Pengajuan Pinjaman',
                'icon' => 'file-input',
                'type' => 'menu',
                'route_name' => 'admin.pengajuan-pinjaman.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Jadwal Pencairan',
                'icon' => 'calendar-clock',
                'type' => 'menu',
                'route_name' => 'admin.jadwal-pencairan.index',
                'roles' => ['admin']
            ],
            [
                'title' => 'Pinjaman Kelompok',
                'icon' => 'circle-dollar-sign',
                'type' => 'menu',
                'route_name' => 'admin.pinjaman-kelompok.index',
                'roles' => ['admin']
            ]
        ];
    }
}