<?php
namespace App\Models;

use App\Enums\Admin\PengajuanPinjaman\EnumTenor;
use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusCicilanKelompok;
use App\Enums\Admin\Status\EnumStatusPinjaman;
use Illuminate\Database\Eloquent\Builder;
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
        'tanggal_mulai',
        'tanggal_jatuh_tempo',
        'kelompok_id',
        'pengajuan_pinjaman_id',
        'jadwal_pencairan_id'
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_status',
        'formatted_tenor',
        'formatted_bunga',
        'formatted_nominal_pinjaman',
        'formatted_tanggal_mulai',
        'formatted_tanggal_jatuh_tempo',
        'formatted_total_nominal_pinjaman',
        'status_pinjaman_selesai',
        'status_pinjaman_menunggak',
        'status_pinjaman_berlangsung',
        'ketua_name',
        'kelompok_name',
        'progres_cicilan',
        'decimal_bunga',
        'total_nominal_pinjaman',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tenor' => EnumTenor::class,
        'status' => EnumStatusPinjaman::class,
        'tanggal_mulai' => 'datetime',
        'tanggal_jatuh_tempo' => 'datetime'
    ];

    /**
     * Relationships
     */
    public function cicilan_kelompok()
    {
        return $this->hasMany(CicilanKelompok::class, 'pinjaman_kelompok_id', 'id');
    }
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id', 'id');
    }
    public function jadwal_pencairan()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_pinjaman_id', 'id');
    }
    public function pengajuan_pinjaman()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_pinjaman_id', 'id');
    }

    /**
     * Scope a query Pinjaman kelompok
     *
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->whereHas('kelompok', function ($qr) use ($keyword) {
            $qr->where('name', 'like', "%{$keyword}%")
                ->orWhereHas('users', function ($nqr) use ($keyword) {
                    $nqr->where('name', 'like', "%{$keyword}%");
                });
        });
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
    public function scopeCicilan_sudah_bayar_count($query)
    {
        return $query->withCount([
            'cicilan_kelompok as cicilan_sudah_bayar_count' => function ($q) {
                $q->where('status', EnumStatusCicilanKelompok::SUDAH_BAYAR);
            }
        ]);
    }
    public function scopePinjaman_jatuh_tempo($query)
    {
        $toleransi_telat_bayar = intval(Settings::getKeySetting(EnumSettingKeys::TOLERANSI_TELAT_BAYAR)->value('value'));
        return $query->where('tanggal_jatuh_tempo', '<=', now()->subDays($toleransi_telat_bayar));
    }
    public function scopeFilterPinjamanBerlangsung($query)
    {
        return $query->where('status', EnumStatusPinjaman::BERLANGSUNG)->count();
    }
    public function scopeFilterJumlahSemuaPinjaman($query)
    {
        return $query->count();
    }
    public function scopeFilterJumlahPinjamanByStatus($query, EnumStatusPinjaman $status)
    {
        return $query->where('status', $status)->count();
    }

    /**
     * Accessor
     */
    // Non formatted
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
    public function progresCicilan(): Attribute
    {
        $cicilan_kelompok = $this->cicilan_kelompok;
        $cicilan_terbayar = $cicilan_kelompok->where('status', EnumStatusCicilanKelompok::SUDAH_BAYAR)->count();
        $jumlah_cicilan = $this->tenor->value;

        return Attribute::make(
            get: fn() => "{$cicilan_terbayar}/{$jumlah_cicilan}"
        );
    }
    public function statusPinjamanSelesai(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPinjaman::SELESAI
        );
    }
    public function statusPinjamanMenunggak(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPinjaman::MENUNGGAK
        );
    }
    public function statusPinjamanBerlangsung(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPinjaman::BERLANGSUNG
        );
    }
    public function totalNominalPinjaman(): Attribute
    {
        $nominal = (1 + $this->decimal_bunga ?? 0) * $this->nominal_pinjaman;
        return Attribute::make(
            get: fn() => $nominal
        );
    }
    public function decimalBunga(): Attribute
    {
        $bunga = ($this->bunga ?? 0) / 100;
        return Attribute::make(
            get: fn() => $bunga
        );
    }


    // Formatted
    public function formattedStatus(): Attribute
    {
        $status = $this->status->value ?? "-";
        return Attribute::make(
            get: fn() => Str::of($status)->ucfirst()->replace("_", " ")
        );
    }
    public function formattedTenor(): Attribute
    {
        $tenor = $this->tenor->value ?? 0;
        return Attribute::make(
            get: fn() => "{$tenor} Bulan"
        );
    }
    public function formattedBunga(): Attribute
    {
        $tenor = $this->bunga ?? 0;
        return Attribute::make(
            get: fn() => "{$tenor} %"
        );
    }
    public function formattedNominalPinjaman(): Attribute
    {
        $nominal = number_format($this->nominal_pinjaman ?? 0, 0, ',', '.');
        return Attribute::make(
            get: fn() => "Rp {$nominal}"
        );
    }
    public function formattedTotalNominalPinjaman(): Attribute
    {
        $nominal = (1 + $this->decimal_bunga) * $this->nominal_pinjaman;
        return Attribute::make(
            get: fn() => "Rp " . number_format($nominal, 0, ',', '.')
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
