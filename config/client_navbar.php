<?php

$navbar_menu = [
    [
        'title' => 'Dashboard',
        'icon' => 'layout-grid',
        'type' => 'menu',
        'route_name' => 'client.dashboard.index',
    ],
    [
        'title' => 'Kelompok',
        'icon' => 'users',
        'type' => 'menu',
        'route_name' => 'client.kelompok.index'
    ],
    [
        'title' => 'Pinjaman',
        'icon' => 'circle-dollar-sign',
        'type' => 'menu',
        'route_name' => 'client.pinjaman-kelompok.index'
    ],
    [
        'title' => 'Pengajuan pinjaman',
        'icon' => 'file-up',
        'type' => 'menu',
        'route_name' => 'client.pengajuan-pinjaman.index'
    ],
];

return collect($navbar_menu);
