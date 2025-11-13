<?php

$sidebar_menu = [
    [
        'title' => 'Menu utama',
        'type' => 'menu-title',
        'roles' => ['admin', 'kepala_institusi', 'akuntan', 'pengelola_dana']
    ],
    [
        'title' => 'Dashboard',
        'icon' => 'home',
        'type' => 'menu',
        'route_name' => 'admin.dashboard.index',
        'roles' => ['admin', 'kepala_institusi', 'akuntan', 'pengelola_dana']
    ],
    [
        'title' => 'Manajemen Pendanaan',
        'type' => 'menu-title',
        'roles' => ['admin', 'kepala_institusi', 'pengelola_dana']
    ],
    [
        'title' => 'Saldo Pendanaan',
        'icon' => 'wallet',
        'type' => 'menu',
        'route_name' => 'admin.pendanaan.index',
        'roles' => ['admin', 'kepala_institusi', 'akuntan', 'pengelola_dana']
    ],
    [
        'title' => 'Manajemen Akun',
        'type' => 'menu-title',
        'roles' => ['admin', 'kepala_institusi']
    ],
    [
        'title' => 'Akun Pengguna',
        'icon' => 'circle-user',
        'type' => 'menu',
        'route_name' => 'admin.users.index',
        'roles' => ['admin', 'kepala_institusi']
    ],
    [
        'title' => 'Kelompok Upk',
        'icon' => 'users-round',
        'type' => 'menu',
        'route_name' => 'admin.kelompok.index',
        'roles' => ['admin', 'kepala_institusi']
    ],
    [
        'title' => 'Manajemen Pinjaman',
        'type' => 'menu-title',
        'roles' => ['admin', 'kepala_institusi', 'akuntan']
    ],
    [
        'title' => 'Pengajuan Pinjaman',
        'icon' => 'file-input',
        'type' => 'menu',
        'route_name' => 'admin.pengajuan-pinjaman.index',
        'roles' => ['admin', 'kepala_institusi']
    ],
    [
        'title' => 'Jadwal Pencairan',
        'icon' => 'calendar-clock',
        'type' => 'menu',
        'route_name' => 'admin.jadwal-pencairan.index',
        'roles' => ['admin', 'kepala_institusi', 'pengelola_dana']
    ],
    [
        'title' => 'Pinjaman Kelompok',
        'icon' => 'circle-dollar-sign',
        'type' => 'menu',
        'route_name' => 'admin.pinjaman-kelompok.index',
        'roles' => ['admin', 'kepala_institusi', 'akuntan']
    ]
];

return collect($sidebar_menu);