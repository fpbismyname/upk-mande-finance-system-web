<?php
namespace App\Models;

use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use App\Enums\Admin\Status\EnumStatusKelompok;
use App\Enums\Admin\Status\EnumStatusPengajuanPinjaman;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Kelompok extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'limit_pinjaman', 'users_id', 'status'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kelompok';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'ketua_name',
        'formatted_status',
        'formatted_name',
        'formatted_name_snake_case',
        'limit_pinjaman_terpakai',
        'sisa_limit_pinjaman',
        'layak_mengajukan_pinjaman'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => EnumStatusKelompok::class,
    ];

    /**
     * Scope a query kelompok
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('limit_pinjaman', 'like', "%{$keyword}%")
            ->orWhereHas('users', fn($qr) => $qr->where('name', 'like', "%{$keyword}%"))
        ;
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
    public function scopeFilterJumlahKelompok($query, EnumStatusKelompok $status)
    {
        return $query->where('status', $status)->count();
    }
    public function scopeFilterJumlahSemuaKelompok($query)
    {
        return $query->count();
    }
    public function scopeFilterJumlahAnggotaKelompok($query)
    {
        $jumlah_kelompok = $query->join('users', 'kelompok.users_id', '=', 'users.id')->count();
        $jumlah_anggota = $query->join('anggota_kelompok', 'kelompok.id', '=', 'anggota_kelompok.kelompok_id')->count();
        return $jumlah_kelompok + $jumlah_anggota;
    }
    public function scopeFilterCicilanKelompokBelumBayar($query)
    {
        return $query->whereHas(
            'pinjaman_kelompok_berlangsung.cicilan_kelompok',
            fn($q) => $q->where('cicilan_kelompok.status', EnumStatusCicilanKelompok::BELUM_BAYAR)
                ->where('cicilan_kelompok.tanggal_jatuh_tempo', '<=', now())
        )->with([
                    'pinjaman_kelompok_berlangsung.cicilan_kelompok' => function ($q) {
                        $q->where('cicilan_kelompok.status', EnumStatusCicilanKelompok::BELUM_BAYAR)
                            ->where('cicilan_kelompok.tanggal_jatuh_tempo', '<=', now());
                    },
                ]);
    }
    public function scopeFilterPinjamanKelompokCount($query)
    {
        return $query->withCount([
            'pinjaman_kelompok as pinjaman_kelompok_count' => function ($q) {
                $q->where('status', EnumStatusPinjaman::SELESAI);
            }
        ]);
    }

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    public function anggota_kelompok()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id');
    }
    public function pengajuan_pinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'kelompok_id');
    }
    public function pengajuan_pinjaman_disetujui()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'kelompok_id')->where('status', EnumStatusPengajuanPinjaman::DISETUJUI);
    }
    public function pinjaman_kelompok()
    {
        return $this->hasMany(PinjamanKelompok::class, 'kelompok_id');
    }
    public function pinjaman_kelompok_berlangsung()
    {
        return $this->hasOne(PinjamanKelompok::class, 'kelompok_id')->whereIn('status', [EnumStatusPinjaman::BERLANGSUNG, EnumStatusPinjaman::MENUNGGAK]);
    }

    /**
     * Accessor
     *
     */
    protected function formattedLimitPinjaman(): Attribute
    {
        $limit_pinjaman = $this->limit_pinjaman ?? 0;
        return Attribute::make(
            get: fn() => "Rp " . number_format($limit_pinjaman, 0, ',', '.'),
        );
    }
    protected function formattedName(): Attribute
    {
        $kelompok_name = $this->name ?? '';
        return Attribute::make(
            get: fn() => Str::of($kelompok_name)->ucfirst(),
        );
    }
    protected function formattedNameSnakeCase(): Attribute
    {
        $kelompok_name = $this->name ?? '';
        return Attribute::make(
            get: fn() => Str::of($kelompok_name)->snake(),
        );
    }
    protected function limitPinjaman(): Attribute
    {
        return Attribute::make(
            get: fn($value) => intval($value),
            set: fn($value) => floatval($value)
        );
    }
    protected function limitPinjamanTerpakai(): Attribute
    {
        $limit_pinjaman_kelompok = $this->limit_pinjaman;
        $nominal_pinjaman_berlangsung = $this->pinjaman_kelompok_berlangsung->nominal_pinjaman ?? 0;
        $limit_pinjaman_terpakai = $limit_pinjaman_kelompok - ($limit_pinjaman_kelompok - $nominal_pinjaman_berlangsung);
        return Attribute::make(
            get: fn() => "Rp " . number_format($limit_pinjaman_terpakai, 0, ",", ".")
        );
    }
    protected function sisaLimitPinjaman(): Attribute
    {
        $limit_pinjaman_kelompok = $this->limit_pinjaman;
        $nominal_pinjaman_berlangsung = $this->pinjaman_kelompok_berlangsung->nominal_pinjaman ?? 0;
        $sisa_limit_pinjaman = $limit_pinjaman_kelompok - $nominal_pinjaman_berlangsung;
        return Attribute::make(
            get: fn() => "Rp " . number_format($sisa_limit_pinjaman, 0, ",", ".")
        );
    }
    /**
     * Get Ketua kelompok
     */
    public function ketuaName(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->users?->name ?? "Tidak ada")->replace("_", " ")->ucfirst()
        );
    }
    /**
     * Get Status kelompok
     */
    public function formattedStatus(): Attribute
    {
        $status_name = $this->status->value ?? "-";
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }
    protected function layakMengajukanPinjaman(): Attribute
    {
        $tidak_ada_pengajuan = $this->pengajuan_pinjaman()->whereNot('status', EnumStatusPengajuanPinjaman::DISETUJUI)->get()->isEmpty();
        $tidak_ada_jadwal_pencairan = $this->pengajuan_pinjaman_disetujui()->whereDoesntHave('jadwal_pencairan', fn($q) => $q->where('status', EnumStatusJadwalPencairan::TELAH_DICAIRKAN))->get()->isEmpty();
        $tidak_ada_pinjaman_berlangsung = $this->pinjaman_kelompok_berlangsung()->get()->isEmpty();
        $bisa_mengajukan = $tidak_ada_pengajuan && $tidak_ada_jadwal_pencairan && $tidak_ada_pinjaman_berlangsung;
        return Attribute::make(
            get: fn() => $bisa_mengajukan
        );
    }
}
