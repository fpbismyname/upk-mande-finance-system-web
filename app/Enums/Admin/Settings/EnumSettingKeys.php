<?php
namespace App\Enums\Admin\Settings;

use Illuminate\Support\Str;

enum EnumSettingKeys: string
{
    case TOLERANSI_TELAT_BAYAR = 'toleransi_telat_bayar';
    case BUNGA_PINJAMAN = 'bunga_pinjaman';
    case MAKSIMAL_LIMIT_PINJAMAN = 'maksimal_limit_pinjaman';
    case MINIMAL_LIMIT_PINJAMAN = 'minimal_limit_pinjaman';
    case KENAIKAN_LIMIT_PER_JUMLAH_PINJAMAN = 'kenaikan_limit_per_jumlah_pinjaman';
    case MAKSIMAL_ANGGOTA_KELOMPOK = 'maksimal_anggota_kelompok';
    case MINIMAL_ANGGOTA_KELOMPOK = 'minimal_anggota_kelompok';
}
