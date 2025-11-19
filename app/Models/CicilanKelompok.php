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
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'cicilan_kelompok';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'nominal_cicilan',
        'bukti_pembayaran',
        'tanggal_dibayar',
        'tanggal_jatuh_tempo',
        'pinjaman_kelompok_id'
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_dibayar' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
        'status' => EnumStatusCicilanKelompok::class
    ];
    /**
     * Scope a query cicilan
     */
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
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        return $query->whereIn('status', [EnumStatusCicilanKelompok::BELUM_BAYAR])
            ->where('tanggal_jatuh_tempo', '<=', now()->subDays($toleransi_telat_bayar));
    }

    /**
     * Relationships
     */
    public function pinjaman_kelompok()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'pinjaman_kelompok_id', 'id');
    }
    public function buktiPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? "-"
        );
    }

    /**
     * Accessor
     * 
     */

    // Non formatted
    public function cicilanJatuhTempo(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        return Attribute::make(
            get: fn() => $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar) <= now()
        );
    }
    public function cicilanBelumBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status !== EnumStatusCicilanKelompok::DIBATALKAN && $this->status !== EnumStatusCicilanKelompok::SUDAH_BAYAR
        );
    }
    public function statusBelumBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::BELUM_BAYAR
        );
    }
    public function statusSudahBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::SUDAH_BAYAR
        );
    }
    public function statusTelatBayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::TELAT_BAYAR
        );
    }
    public function statusDibatalkan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusCicilanKelompok::DIBATALKAN
        );
    }
    public function decimalHariTelatBayar(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar)->diffInDays($this->tanggal_dibayar);
        return Attribute::make(
            get: fn() => round(max(0, $hari_telat_bayar))
        );
    }
    public function cicilanTelatSudahBayar(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->addDays($toleransi_telat_bayar) <= $this->tanggal_dibayar;
        return Attribute::make(
            get: fn() => $hari_telat_bayar === true
        );
    }

    // Formatted
    public function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->status->value)->ucfirst()->replace("_", " ")
        );
    }
    public function formattedTanggalDibayar(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tanggal_dibayar ? Carbon::parse($this->tanggal_dibayar)->format('d M Y | H:i') : '-'
        );
    }
    public function formattedTanggalJatuhTempo(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->tanggal_jatuh_tempo ? Carbon::parse($this->tanggal_jatuh_tempo)->format('d M Y | H:i') : '-'
        );
    }
    public function formattedNominalCicilan(): Attribute
    {
        $nominal = $this->nominal_cicilan ?? 0;
        return Attribute::make(
            get: fn() => "Rp. " . number_format($nominal, 0, ",", ".")
        );
    }
    public function formattedHariTelatBayar(): Attribute
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        $hari_telat_bayar = $this->tanggal_jatuh_tempo->diffInDays($this->tanggal_dibayar);
        return Attribute::make(
            get: fn() => round(max(0, $hari_telat_bayar)) . " hari"
        );
    }
}
