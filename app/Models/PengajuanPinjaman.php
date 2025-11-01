<?php

namespace App\Models;

use App\Models\Status\StatusPengajuanPinjaman;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengajuanPinjaman extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_proposal',
        'nominal_pinjaman',
        'tenor',
        'pengajuan_pada',
        'disetujui_pada',
        'ditolak_pada',
        'catatan',
        'status_id',
        'kelompok_id'
    ];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_pinjaman';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'kelompok_name',
        'status_name',
        'formatted_nominal_pinjaman',
        'formatted_tenor',
        'formatted_pengajuan',
        'formatted_disetujui',
        'formatted_ditolak',
        'on_proses_pengajuan'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    /**
     *  Relationships : users, status_pengajuan_pinjaman
     * 
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
    public function status_pengajuan_pinjaman()
    {
        return $this->belongsTo(StatusPengajuanPinjaman::class, 'status_id', 'id');
    }

    /**
     *  Accessor
     * 
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
        $status_name = $this->status_pengajuan_pinjaman?->name;
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
    public function formattedPengajuan(): Attribute
    {
        $value = $this->attributes['pengajuan_pada'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get formatted_disetujui_pada
    public function formattedDisetujui(): Attribute
    {
        $value = $this->attributes['disetujui_pada'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get formatted_ditolak_pada
    public function formattedDitolak(): Attribute
    {
        $value = $this->attributes['ditolak_pada'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get is_approved pinjaman
    public function onProsesPengajuan(): Attribute
    {
        $status = $this->status_pengajuan_pinjaman?->name;
        return Attribute::make(
            get: fn() => $status == 'proses_pengajuan'
        );
    }
}
