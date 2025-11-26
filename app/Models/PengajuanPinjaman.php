<?php
namespace App\Models;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
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
        'kelompok_id',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tenor' => EnumTenor::class,
        'status' => EnumStatusPengajuanPinjaman::class,
        'tanggal_pengajuan' => 'datetime',
        'tanggal_disetujui' => 'datetime',
        'tanggal_ditolak' => 'datetime'
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
        'status_disetujui',
        'status_dibatalkan',
        'has_pengajuan_dalam_proses',
        'formatted_link_file_proposal'
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
        return $this->hasOne(JadwalPencairan::class, 'pengajuan_pinjaman_id', 'id');
    }

    /**
     * Scope a query pengajuan pinjaman
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('nominal_pinjaman', 'like', "%{$keyword}%")
            ->orWhere('nominal_pinjaman', 'like', "%{$keyword}%")
            ->orWhereHas('kelompok', fn($qr) => $qr->whereHas('users', fn($nqr) => $nqr->where('name', 'like', "%{$keyword}%")))
            ->orWhereHas('kelompok', fn($qr) => $qr->where('name', 'like', "%{$keyword}%"));
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
    public function scopeFilterJumlahSemuaPengajuan($query)
    {
        return $query->count();
    }
    public function scopeFilterJumlahPengajuanByStatus($query, EnumStatusPengajuanPinjaman $status)
    {
        return $query->where('status', $status)->count();
    }

    /**
     *  Accessor
     *
     */
    protected function nominalPinjaman(): Attribute
    {
        return Attribute::make(
            get: fn($value) => intval($value),
            set: fn($value) => floatval($value)
        );
    }
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
    // get dibatalkan
    public function statusDibatalkan(): Attribute
    {
        $status = $this->status;
        return Attribute::make(
            get: fn() => $status === EnumStatusPengajuanPinjaman::DIBATALKAN
        );
    }

    // Formatted
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
    public function formattedLinkFileProposal(): Attribute
    {
        $url = route('storage.get-file', ['path' => $this->file_proposal]);
        return Attribute::make(
            get: fn() => $url
        );
    }
    // Event eloquent hook
    public static function booted()
    {
        static::creating(function ($model) {
            $model->tanggal_pengajuan = now();
        });
    }
}
