<?php

namespace App\Models;

use App\Models\Status\StatusJadwalPencairan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class JadwalPencairan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jadwal_pencairan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tanggal_pencairan', 'status_id', 'pengajuan_id', 'kelompok_id'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'kelompok_name',
        'ketua_name',
        'status_name',
        'formatted_nominal_pinjaman',
        'formatted_tenor',
        'formatted_pengajuan',
        'formatted_disetujui',
        'formatted_ditolak',
    ];

    /**
     * Relationships
     * 
     */
    public function status_jadwal_pencairan()
    {
        return $this->belongsTo(StatusJadwalPencairan::class, 'status_id', 'id');
    }
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
    public function pengajuan_pinjaman()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_id', 'id');
    }


    /**
     * Accessor
     */
    // get kelompok_name
    public function kelompokName(): Attribute
    {
        $kelompok_name = $this->kelompok->name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($kelompok_name)
        );
    }
    // get ketua_name
    public function ketuaName(): Attribute
    {
        $ketua = $this->kelompok->ketua_name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($ketua)
        );
    }
    // get status_name
    public function statusName(): Attribute
    {
        $status_name = $this->status_jadwal_pencairan?->name;
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }
    // get formatted_nominal_pinjaman
    public function formattedNominalPinjaman(): Attribute
    {
        $nominal = $this->attributes['nominal_pinjaman'];
        return Attribute::make(
            get: fn() => "Rp " . number_format($nominal, 0, ",", ".")
        );
    }
    // get formatted_tenor
    public function formattedTenor(): Attribute
    {
        $tenor = $this->attributes['tenor'];
        return Attribute::make(
            get: fn() => Str::of("{$tenor} bulan")
        );
    }
    // get formatted_pengajuan_pada
    public function formattedTanggalPencairan(): Attribute
    {
        $value = $this->attributes['tanggal_pencairan'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
}
