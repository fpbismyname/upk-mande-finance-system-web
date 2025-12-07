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
        'title' => 'Manajemen Rekening',
        'type' => 'menu-title',
        'roles' => ['kepala_institusi', 'pengelola_dana', 'akuntan']
    ],
    [
        'title' => 'Rekening Pendanaan',
        'icon' => 'wallet',
        'type' => 'menu',
        'route_name' => 'admin.rekening-pendanaan.index',
        'roles' => ['kepala_institusi', 'pengelola_dana']
    ],
    [
        'title' => 'Rekening Akuntan',
        'icon' => 'wallet',
        'type' => 'menu',
        'route_name' => 'admin.rekening-akuntan.index',
        'roles' => ['kepala_institusi', 'akuntan']
    ],
    [
        'title' => 'Manajemen Akun',
        'type' => 'menu-title',
        'roles' => ['admin', 'kepala_institusi']
    ],
    [
        'title' => 'Pengajuan keanggotaan',
        'icon' => 'file-user',
        'type' => 'menu',
        'route_name' => 'admin.pengajuan-keanggotaan.index',
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
        'roles' => ['kepala_institusi', 'akuntan', 'pengelola_dana']
    ],
    [
        'title' => 'Pengajuan Pinjaman',
        'icon' => 'file-input',
        'type' => 'menu',
        'route_name' => 'admin.pengajuan-pinjaman.index',
        'roles' => ['kepala_institusi', 'akuntan']
    ],
    [
        'title' => 'Jadwal Pencairan',
        'icon' => 'calendar-clock',
        'type' => 'menu',
        'route_name' => 'admin.jadwal-pencairan.index',
        'roles' => ['kepala_institusi', 'pengelola_dana', 'akuntan']
    ],
    [
        'title' => 'Pinjaman Kelompok',
        'icon' => 'circle-dollar-sign',
        'type' => 'menu',
        'route_name' => 'admin.pinjaman-kelompok.index',
        'roles' => ['kepala_institusi', 'akuntan']
    ]
];

return collect($sidebar_menu);