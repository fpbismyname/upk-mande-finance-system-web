<?php
namespace App\Models;

use App\Enums\Admin\Settings\EnumSettingKeys;
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
    protected $fillable = [
        'name',
        'limit_per_anggota',
        'users_id',
        'status'
    ];
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
        'formatted_status',
        'formatted_name',
        'formatted_name_snake_case',
        'formatted_tanggal_dibuat',
        'formatted_limit_pinjaman_kelompok',
        'decimal_limit_pinjaman_kelompok',
        'decimal_jumlah_anggota_kelompok',
        'decimal_sisa_limit_pinjaman',
        'decimal_limit_pinjaman_terpakai',
        'ketua_name',
        'dapat_menambahkan_anggota_kelompok',
        'layak_mengajukan_pinjaman',
        'anggota_kelompok_lengkap',
        'jumlah_anggota_kelompok'
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
            ->orWhere('limit_per_anggota', 'like', "%{$keyword}%")
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
    public function scopeJumlah_anggota_kelompok($query)
    {
        return $query->join('anggota_kelompok', 'kelompok.id', '=', 'anggota_kelompok.kelompok_id');
    }
    public function scopeCicilan_belum_bayar($query)
    {
        return $query->whereHas(
            'pinjaman_kelompok_berlangsung.cicilan_kelompok',
            function ($q) {
                $q->where('cicilan_kelompok.status', EnumStatusCicilanKelompok::BELUM_BAYAR)
                    ->where('cicilan_kelompok.tanggal_jatuh_tempo', '<=', now());
            }
        )->with([
                    'pinjaman_kelompok_berlangsung.cicilan_kelompok' => function ($q) {
                        $q->where('cicilan_kelompok.status', EnumStatusCicilanKelompok::BELUM_BAYAR)
                            ->where('cicilan_kelompok.tanggal_jatuh_tempo', '<=', now());
                    },
                ]);
    }
    public function scopePinjaman_kelompok_selesai_count($query)
    {
        return $query->withCount([
            'pinjaman_kelompok as pinjaman_kelompok_selesai_count' => function ($q) {
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
        return $this->hasOne(PengajuanPinjaman::class, 'kelompok_id')->where('status', EnumStatusPengajuanPinjaman::DISETUJUI);
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

    // Non formatted
    protected function decimalLimitPinjamanKelompok(): Attribute
    {
        $limit_pinjaman_anggota = $this->limit_per_anggota;
        $jumlah_anggota_kelompok = $this->anggota_kelompok()->count();
        $total_limit_pinjaman_kelompok = $limit_pinjaman_anggota * $jumlah_anggota_kelompok;
        return Attribute::make(
            get: fn() => floatval($total_limit_pinjaman_kelompok),
        );
    }
    protected function decimalSisaLimitPinjaman(): Attribute
    {
        $pinjaman_berlangsung = $this->pinjaman_kelompok_berlangsung?->nominal_pinjaman ?? 0;
        $sisa_limit = $this->decimal_limit_pinjaman_kelompok - $pinjaman_berlangsung;
        return Attribute::make(
            get: fn() => floatval($sisa_limit),
        );
    }
    protected function decimalLimitPinjamanTerpakai(): Attribute
    {
        $limit_terpakai = $this->pinjaman_kelompok_berlangsung?->nominal_pinjaman ?? 0;
        return Attribute::make(
            get: fn() => floatval($limit_terpakai),
        );
    }
    public function decimalJumlahAnggotaKelompok(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->anggota_kelompok()->count()
        );
    }
    protected function ketuaName(): Attribute
    {
        $ketua_name = $this->users->name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($ketua_name)->ucfirst()
        );
    }
    protected function limitPerAnggota(): Attribute
    {
        return Attribute::make(
            get: fn($value) => intval($value),
            set: fn($value) => floatval($value)
        );
    }
    protected function anggotaKelompokLengkap(): Attribute
    {
        $minimal_anggota_kelompok = intval(Settings::getKeySetting(EnumSettingKeys::MINIMAL_ANGGOTA_KELOMPOK)->value('value'));
        $maksimal_anggota_kelompok = intval(Settings::getKeySetting(EnumSettingKeys::MAKSIMAL_ANGGOTA_KELOMPOK)->value('value'));

        $jumlah_anggota_kelompok = $this->anggota_kelompok()->count();

        $anggota_kelompok_memenuhi_syarat = $jumlah_anggota_kelompok >= $minimal_anggota_kelompok && $jumlah_anggota_kelompok <= $maksimal_anggota_kelompok;
        return Attribute::make(
            get: fn() => $anggota_kelompok_memenuhi_syarat
        );
    }
    protected function layakMengajukanPinjaman(): Attribute
    {
        $pinjaman_selesai = $this->pinjaman_kelompok()->get()->every(function ($pinjaman) {
            return !in_array($pinjaman->status, [EnumStatusPinjaman::BERLANGSUNG, ENumStatusPinjaman::MENUNGGAK]);
        });
        $pengajuan_selesai = $this->pengajuan_pinjaman()->get()->every(function ($pengajuan) {
            return !in_array($pengajuan->status, [EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN]);
        }) ?? true;
        $tidak_ada_jadwal_pencairan = $this->pengajuan_pinjaman()->get()->every(function ($pengajuan) {
            return $pengajuan->jadwal_pencairan()->get()->every(function ($jadwal) {
                return !in_array($jadwal->status, [EnumStatusJadwalPencairan::TERJADWAL, EnumStatusJadwalPencairan::BELUM_TERJADWAL]);
            });
        }) ?? true;
        $layak_meminjam = $pinjaman_selesai === true && $pengajuan_selesai === true && $this->anggota_kelompok_lengkap === true && $tidak_ada_jadwal_pencairan == true;
        return Attribute::make(
            get: fn() => $layak_meminjam
        );
    }
    public function jumlahAnggotaKelompok(): Attribute
    {
        // Maksimal jumlah anggota
        $max_anggota = Settings::getKeySetting(EnumSettingKeys::MAKSIMAL_ANGGOTA_KELOMPOK)->value('value');
        return Attribute::make(
            get: fn() => $this->anggota_kelompok()->count() . "/{$max_anggota}"
        );
    }
    public function dapatMenambahkanAnggotaKelompok(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->pinjaman_kelompok_berlangsung == null
            && $this->pengajuan_pinjaman()->search_by_column('status', EnumStatusPengajuanPinjaman::PROSES_PENGAJUAN)->get()->isEmpty()
            && $this->pengajuan_pinjaman_disetujui->jadwal_pencairan()->search_by_column('status', [EnumStatusJadwalPencairan::BELUM_TERJADWAL, EnumStatusJadwalPencairan::TERJADWAL])->get()->isEmpty()
        );
    }

    // Formatted
    protected function formattedLimitPerAnggota(): Attribute
    {
        $limit_per_anggota = $this->limit_per_anggota ?? 0;
        return Attribute::make(
            get: fn() => "Rp " . number_format($limit_per_anggota, 0, ',', '.'),
        );
    }

    protected function formattedLimitPinjamanKelompok(): Attribute
    {
        $limit_pinjaman_anggota = $this->limit_per_anggota;
        $jumlah_anggota_kelompok = $this->anggota_kelompok()->count();
        $total_limit_pinjaman_kelompok = $limit_pinjaman_anggota * $jumlah_anggota_kelompok;
        return Attribute::make(
            get: fn() => "Rp " . number_format($total_limit_pinjaman_kelompok, 0, ',', '.'),
        );
    }
    protected function formattedSisaLimitPinjaman(): Attribute
    {
        $sisa_limit = $this->decimal_limit_pinjaman_kelompok - $this->pinjaman_kelompok_berlangsung()->first()?->nominal_pinjaman;
        return Attribute::make(
            get: fn() => "Rp " . number_format($sisa_limit, 0, ',', '.')
        );
    }
    protected function formattedLimitPinjamanTerpakai(): Attribute
    {
        $limit_terpakai = $this->pinjaman_kelompok_berlangsung()->first()?->nominal_pinjaman ?? 0;
        return Attribute::make(
            get: fn() => "Rp " . number_format($limit_terpakai, 0, ',', '.')
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
    public function formattedStatus(): Attribute
    {
        $status_name = $this->status->value ?? "-";
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }
    public function formattedTanggalDibuat(): Attribute
    {
        $tanggal_dibuat = $this->created_at->format('d M Y | H:i') ?? "-";
        return Attribute::make(
            get: fn() => $tanggal_dibuat
        );
    }
}
