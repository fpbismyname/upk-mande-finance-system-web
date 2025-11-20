<?php

namespace App\Models;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class CicilanKelompok extends Model
{
    protected $table = 'cicilan_kelompok';

    protected $fillable = [
        'status',
        'nominal_cicilan',
        'bukti_pembayaran',
        'tanggal_dibayar',
        'tanggal_jatuh_tempo',
        'pinjaman_kelompok_id'
    ];

    protected $appends = [
        'formatted_status',
        'formatted_tanggal_dibayar',
        'formatted_tanggal_jatuh_tempo',
        'formatted_nominal_cicilan',
        'formatted_hari_telat_bayar',
        'decimal_hari_telat_bayar',
        'cicilan_jatuh_tempo',
        'cicilan_belum_bayar',
        'cicilan_telat_sudah_bayar',
        'status_belum_bayar',
        'status_sudah_bayar',
        'status_telat_bayar',
        'status_dibatalkan',
    ];

    protected $casts = [
        'tanggal_dibayar' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'status' => EnumStatusCicilanKelompok::class
    ];

    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('nominal_cicilan', 'like', "%{$keyword}%");
    }

    public function scopeSearch_by_column($query, $column, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        if (is_array($keyword)) {
            return $query->whereIn($column, $keyword);
        }
        return $query->where($column, $keyword);
    }

    public function scopeCicilan_jatuh_tempo($query)
    {
        // Mengambil nilai toleransi telat bayar dari pengaturan aplikasi.
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        return $query->whereIn('status', [EnumStatusCicilanKelompok::BELUM_BAYAR])
            ->where('tanggal_jatuh_tempo', '<=', now()->subDays($toleransi_telat_bayar));
    }

    public function pinjaman_kelompok()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'pinjaman_kelompok_id', 'id');
    }

    /**
     * Accessor untuk mendapatkan bukti pembayaran.
     */
    public function buktiPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? "-"
        );
    }

    /**
     * Accessor untuk memeriksa apakah cicilan sudah jatuh tempo.
     */
    public function cicilanJatuhTempo(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        return Attribute::make(
            get: fn() => $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar) <= now()
        );
    }

    /**
     * Accessor untuk memeriksa apakah cicilan belum dibayar.
     */
    public function cicilanBelumBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status !== EnumStatusCicilanKelompok::DIBATALKAN && $this->status !== EnumStatusCicilanKelompok::SUDAH_BAYAR
        );
    }

    /**
     * Accessor untuk memeriksa apakah status cicilan adalah BELUM_BAYAR.
     */
    public function statusBelumBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::BELUM_BAYAR
        );
    }

    /**
     * Accessor untuk memeriksa apakah status cicilan adalah SUDAH_BAYAR.
     */
    public function statusSudahBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::SUDAH_BAYAR
        );
    }

    /**
     * Accessor untuk memeriksa apakah status cicilan adalah TELAT_BAYAR.
     */
    public function statusTelatBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::TELAT_BAYAR
        );
    }

    /**
     * Accessor untuk memeriksa apakah status cicilan adalah DIBATALKAN.
     */
    public function statusDibatalkan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::DIBATALKAN
        );
    }

    /**
     * Accessor untuk mendapatkan jumlah hari telat bayar dalam format desimal.
     */
    public function decimalHariTelatBayar(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        // @note Logika ini menghitung hari telat bayar berdasarkan tanggal dibayar.
        // Jika tanggal dibayar null, ini akan menyebabkan error atau hasil yang tidak akurat.
        // Perlu dipastikan tanggal_dibayar tidak null atau ditangani dengan baik.
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar)->diffInDays($this->tanggal_dibayar);
        return Attribute::make(
            get: fn() => round(max(0, $hari_telat_bayar))
        );
    }

    /**
     * Accessor untuk memeriksa apakah cicilan dibayar setelah melewati batas toleransi telat bayar.
     */
    public function cicilanTelatSudahBayar(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        // @note Sama seperti decimalHariTelatBayar, logika ini bergantung pada tanggal_dibayar yang tidak null.
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar) <= $this->tanggal_dibayar;
        return Attribute::make(
            get: fn() => $hari_telat_bayar === true
        );
    }

    /**
     * Accessor untuk mendapatkan status cicilan dalam format yang mudah dibaca.
     */
    public function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->status->value)->ucfirst()->replace("_", " ")
        );
    }

    /**
     * Accessor untuk mendapatkan tanggal dibayar dalam format 'd M Y | H:i'.
     */
    public function formattedTanggalDibayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tanggal_dibayar ? Carbon::parse($this->tanggal_dibayar)->format('d M Y | H:i') : '-'
        );
    }

    /**
     * Accessor untuk mendapatkan tanggal jatuh tempo dalam format 'd M Y | H:i'.
     */
    public function formattedTanggalJatuhTempo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tanggal_jatuh_tempo ? Carbon::parse($this->tanggal_jatuh_tempo)->format('d M Y | H:i') : '-'
        );
    }

    /**
     * Accessor untuk mendapatkan nominal cicilan dalam format mata uang (Rp.).
     */
    public function formattedNominalCicilan(): Attribute
    {
        $nominal = $this->nominal_cicilan ?? 0;
        return Attribute::make(
            get: fn() => "Rp. " . number_format($nominal, 0, ",", ".")
        );
    }

    /**
     * Accessor untuk mendapatkan jumlah hari telat bayar dalam format teks (e.g., "5 hari").
     */
    public function formattedHariTelatBayar(): Attribute
    {
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->diffInDays($this->tanggal_dibayar);
        return Attribute::make(
            get: fn() => round(max(0, $hari_telat_bayar)) . " hari"
        );
    }
}
