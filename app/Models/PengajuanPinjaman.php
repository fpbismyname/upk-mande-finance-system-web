<?php

namespace App\Models;

use App\Enum\Admin\PengajuanPinjaman\EnumTenor;
use App\Enum\Admin\Status\EnumStatusPengajuanPinjaman;
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
        'tanggal_pengajuan',
        'tanggal_disetujui',
        'tanggal_ditolak',
        'catatan',
        'status',
        'kelompok_id'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tenor' => EnumTenor::class,
        'status' => EnumStatusPengajuanPinjaman::class
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
        'formatted_status',
        'formatted_nominal_pinjaman',
        'formatted_tenor',
        'formatted_tanggal_pengajuan',
        'formatted_tanggal_disetujui',
        'formatted_tanggal_ditolak',
        'status_dalam_proses_pengajuan',
        'status_ditolak',
        'status_disetujui'
    ];

    /**
     *  Relationships
     * 
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
    public function jadwal_pencairan()
    {
        return $this->hasMany(JadwalPencairan::class, 'pengajuan_pinjaman_id', 'id');
    }

    /**
     * Scope a query pengajuan pinjaman
     */
    public function scopeFilter($query, $keyword)
    {
        return $query->where('nominal_pinjaman', 'like', "%{$keyword}%")
            ->orWhere('nominal_pinjaman', 'like', "%{$keyword}%")
            ->orWhereHas('kelompok', fn($qr) => $qr->whereHas('users', fn($nqr) => $nqr->where('name', 'like', "%{$keyword}%")))
            ->orWhereHas('kelompok', fn($qr) => $qr->where('name', 'like', "%{$keyword}%"));
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
    public function formattedStatus(): Attribute
    {
        $status_name = $this->status->value ?? "-";
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }
    // Anchor get formatted_nominal_pinjaman
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
        $tenor = $this->tenor->value ?? "0";
        return Attribute::make(
            get: fn() => Str::of("{$tenor} bulan")
        );
    }
    // get formatted_pengajuan_pada
    public function formattedTanggalPengajuan(): Attribute
    {
        $value = $this->attributes['tanggal_pengajuan'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get formatted_disetujui_pada
    public function formattedTanggalDisetujui(): Attribute
    {
        $value = $this->attributes['tanggal_disetujui'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get formatted_ditolak_pada
    public function formattedTanggalDitolak(): Attribute
    {
        $value = $this->attributes['tanggal_ditolak'];
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }
    // get dalam_proses_pengajuan
    public function statusDalamProsesPengajuan(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn() => $status === EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN
        );
    }
    // get ditolak
    public function statusDitolak(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn() => $status === EnumStatusPengajuanPinjaman::DITOLAK
        );
    }
    // get disetujui
    public function statusDisetujui(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn() => $status === EnumStatusPengajuanPinjaman::DISETUJUI
        );
    }
}
