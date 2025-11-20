<?php

namespace App\Models;

use App\Enums\Admin\Settings\EnumSettingKeys;
use App\Enums\Admin\Status\EnumStatusJadwalPencairan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class JadwalPencairan extends Model
{
    protected $table = 'jadwal_pencairan';
    protected $fillable = ['tanggal_pencairan', 'status', 'pengajuan_pinjaman_id'];
    protected $appends = [
        'kelompok_name',
        'ketua_name',
        'formatted_status',
        'formatted_tanggal_pencairan',
        'formatted_datetimelocalTanggalPencairan',
        'formatted_pengajuan_status',
        'formatted_pengajuan_nominal',
        'formatted_pengajuan_tenor',
        'formatted_pengajuan_disetujui',
        'status_telah_dicairkan',
        'status_terjadwal',
        'status_belum_terjadwal'
    ];


    public function pengajuan_pinjaman()
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'pengajuan_pinjaman_id', 'id');
    }

    protected $casts = [
        'status' => EnumStatusJadwalPencairan::class,
        'tanggal_pencairan' => 'datetime'
    ];

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
        })->orWhereHas('pengajuan_pinjaman', function ($qr) use ($keyword) {
            $qr->where('nominal_pinjaman', 'like', "%{$keyword}%");
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


    public function scopeFilterTenorPengajuan($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->whereHas('pengajuan_pinjaman', function ($qr) use ($keyword) {
            $qr->where('tenor', $keyword);
        });
    }


    public function scopeFilterStatusPengajuan($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->whereHas('pengajuan_pinjaman', function ($qr) use ($keyword) {
            $qr->where('status', $keyword);
        });
    }


    public function scopeFilterStatusJadwal($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('status', $keyword);
    }


    public function scopeJadwal_pencairan_jatuh_tempo($query)
    {
        return $query->with(['pengajuan_pinjaman.kelompok'])
            ->where('tanggal_pencairan', '<=', now())
            ->where('status', EnumStatusJadwalPencairan::TERJADWAL);
    }


    public function kelompokName(): Attribute
    {
        $kelompok_name = $this->pengajuan_pinjaman->kelompok->formatted_name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($kelompok_name)
        );
    }


    public function ketuaName(): Attribute
    {
        $ketua = $this->pengajuan_pinjaman->kelompok->ketua_name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($ketua)
        );
    }



    public function pengajuanNominal(): Attribute
    {
        $nominal = $this->pengajuan_pinjaman->nominal_pinjaman;
        return Attribute::make(
            get: fn() => $nominal
        );
    }



    public function pengajuanTenor(): Attribute
    {
        $nominal = $this->pengajuan_pinjaman->tenor;
        return Attribute::make(
            get: fn() => $nominal
        );
    }


    public function formattedStatus(): Attribute
    {
        $status_name = $this->status->value ?? '-';
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }



    public function formattedPengajuanStatus(): Attribute
    {
        $status_name = $this->pengajuan_pinjaman->status->value ?? '-';
        return Attribute::make(
            get: fn() => Str::of($status_name)->replace("_", " ")->ucfirst()
        );
    }



    public function formattedPengajuanNominal(): Attribute
    {
        $nominal = $this->pengajuan_pinjaman->nominal_pinjaman ?? 0;
        return Attribute::make(
            get: fn() => "Rp " . number_format($nominal, 0, ",", ".")
        );
    }

    public function formattedPengajuanTenor(): Attribute
    {
        $tenor = $this->pengajuan_pinjaman->tenor->value ?? '-';
        return Attribute::make(
            get: fn() => Str::of("{$tenor} bulan")
        );
    }

    public function formattedPengajuanTanggalDisetujui(): Attribute
    {
        $tanggal_disetujui = $this->pengajuan_pinjaman->formatted_tanggal_disetujui;
        return Attribute::make(
            get: fn() => $tanggal_disetujui
        );
    }

    public function formattedTanggalPencairan(): Attribute
    {
        $value = $this->tanggal_pencairan;
        return Attribute::make(
            get: fn() => $value ? Carbon::parse($value)->format(' d M Y | H:i') : "-"
        );
    }

    public function statusTelahDicairkan(): Attribute
    {
        $status = $this->status === EnumStatusJadwalPencairan::TELAH_DICAIRKAN;
        return Attribute::make(
            get: fn() => $status
        );
    }

    public function statusTerjadwal(): Attribute
    {
        $status = $this->status === EnumStatusJadwalPencairan::TERJADWAL;
        return Attribute::make(
            get: fn() => $status
        );
    }

    public function statusBelumTerjadwal(): Attribute
    {
        $status = $this->status === EnumStatusJadwalPencairan::BELUM_TERJADWAL;
        return Attribute::make(
            get: fn() => $status
        );
    }

    public function formattedDatetimelocalTanggalPencairan(): Attribute
    {
        $tanggal = $this->tanggal_pencairan ? $this->tanggal_pencairan->format('Y-m-d\TH:i') : "";
        return Attribute::make(
            get: fn() => $tanggal
        );
    }
}
