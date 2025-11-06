<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class PinjamanKelompok extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pinjaman_kelompok';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status',
        'tenor',
        'bunga',
        'nominal_pinjaman',
        'nominal_pinjaman_final',
        'tanggal_mulai',
        'tanggal_jatuh_tempo',
        'kelompok_id'
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'ketua_name',
        'kelompok_name',
        'formatted_status',
        'formatted_tenor',
        'formatted_bunga',
        'formatted_nominal_pinjaman',
        'formatted_nominal_pinjaman_final',
        'formatted_tanggal_mulai',
        'formatted_tanggal_jatuh_tempo'
    ];

    /**
     * Relationships
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
    public function cicilan_kelompok()
    {
        return $this->hasMany(CicilanKelompok::class, 'pinjaman_kelompok_id', 'id');
    }

    /**
     * Scope a query Pinjaman kelompok
     *
     */
    public function scopeFilter($query, $keyword)
    {
        return $query->whereHas('kelompok', function ($qr) use ($keyword) {
            $qr->where('name', 'like', "%{$keyword}%")
                ->orWhereHas('users', function ($nqr) use ($keyword) {
                    $nqr->where('name', 'like', "%{$keyword}%");
                });
        });
    }
    public function scopeFilterTenor($query, $keyword)
    {
        return $query->where('tenor', $keyword);
    }
    public function scopeFilterStatus($query, $keyword)
    {
        return $query->where('status', $keyword);
    }

    /**
     * Get the formatted data
     */
    // get kelompok name
    public function kelompokName(): Attribute
    {
        $name = $this->kelompok->name ?? "-";
        return Attribute::make(
            get: fn() => $name
        );
    }
    // get ketua name
    public function ketuaName(): Attribute
    {
        $name = $this->kelompok->ketua_name ?? "-";
        return Attribute::make(
            get: fn() => $name
        );
    }
    // get status
    public function formattedStatus(): Attribute
    {
        $status = $this->status ?? "-";
        return Attribute::make(
            get: fn() => Str::of($status)->ucfirst()->replace("_", " ")
        );
    }
    // get tenor
    public function formattedTenor(): Attribute
    {
        $tenor = $this->tenor ?? 0;
        return Attribute::make(
            get: fn() => "{$tenor} Bulan"
        );
    }
    // get bunga
    public function formattedBunga(): Attribute
    {
        $tenor = $this->bunga ?? 0;
        return Attribute::make(
            get: fn() => "{$tenor} %"
        );
    }
    // get nominal_pinjaman
    public function formattedNominalPinjaman(): Attribute
    {
        $nominal = number_format($this->nominal_pinjaman ?? 0, 0, ',', '.');
        return Attribute::make(
            get: fn() => "Rp {$nominal}"
        );
    }
    // get nominal_pinjaman_final
    public function formattedNominalPinjamanFinal(): Attribute
    {
        $nominal = number_format($this->nominal_pinjaman_final ?? 0, 0, ',', '.');
        return Attribute::make(
            get: fn() => "Rp {$nominal}"
        );
    }
    // get tanggal_mulai
    public function formattedTanggalMulai(): Attribute
    {
        $tanggal = $this->tanggal_mulai ? Carbon::parse($this->tanggal_mulai)->format('d M Y | H:i') : "-";
        return Attribute::make(
            get: fn() => $tanggal
        );
    }
    // get tanggal_mulai
    public function formattedTanggalJatuhTempo(): Attribute
    {
        $tanggal = $this->tanggal_jatuh_tempo ? Carbon::parse($this->tanggal_jatuh_tempo)->format('d M Y | H:i') : "-";
        return Attribute::make(
            get: fn() => $tanggal
        );
    }
}
