<?php

$navbar_menu = [
    [
        'title' => 'Dashboard',
        'icon' => 'layout-grid',
        'type' => 'menu',
        'roles' => ['anggota'],
        'route_name' => 'client.dashboard.index',
    ],
    [
        'title' => 'Kelompok',
        'icon' => 'users',
        'type' => 'menu',
        'roles' => ['anggota'],
        'route_name' => 'client.kelompok.index'
    ],
    [
        'title' => 'Pinjaman',
        'icon' => 'circle-dollar-sign',
        'type' => 'menu',
        'roles' => ['anggota'],
        'route_name' => 'client.pinjaman-kelompok.index'
    ],
    [
        'title' => 'Pengajuan pinjaman',
        'icon' => 'file-up',
        'type' => 'menu',
        'roles' => ['anggota'],
        'route_name' => 'client.pengajuan-pinjaman.index'
    ],
    [
        'title' => 'Pengajuan keanggotaan',
        'icon' => 'file-user',
        'type' => 'menu',
        'roles' => ['anggota'],
        'route_name' => 'client.pengajuan-keanggotaan.index'
    ],
];

return collect($navbar_menu);
