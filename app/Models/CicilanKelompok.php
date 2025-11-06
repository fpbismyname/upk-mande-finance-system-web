<?php

namespace App\Models;

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
        'formatted_nominal_cicilan'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_dibayar' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime',
    ];
    /**
     * Scope a query cicilan
     */
    public function scopeFilter($query, $keyword)
    {
        return $query->where('nominal_cicilan', 'like', "%{$keyword}%");
    }
    public function scopeFilterStatus($query, $keyword)
    {
        return $query->where('status', $keyword);
    }
    /**
     * Relationships
     */
    public function pinjaman_kelompok()
    {
        return $this->belongsTo(PinjamanKelompok::class, 'pinjaman_kelompok_id', 'id');
    }
    /**
     * Accessor
     */
    public function buktiPembayaran(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? "-"
        );
    }
    public function formattedStatus(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->status)->ucfirst()->replace("_", " ")
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
}
