<?php

namespace App\Models;

use App\Models\Status\StatusKelompok;
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
    protected $fillable = ['name', 'limit_pinjaman', 'ketua_id', 'status_id'];
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
    protected $appends = ['ketua_name', 'status_name'];

    /**
     * Relationships : users, status_kelompoks, anggota_kelompoks, pengajuan pinjaman
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'ketua_id', 'id');
    }
    public function status_kelompok()
    {
        return $this->belongsTo(StatusKelompok::class, 'status_id', 'id');
    }
    public function anggota_kelompok()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id');
    }
    public function pengajuan_pinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'kelompok_id', 'id');
    }

    /**
     * Accessor
     * 
     */

    /**
     * Get formatted limit pinjaman
     */
    protected function formattedLimitPinjaman(): Attribute
    {
        $limit_pinjaman = $this->attributes['limit_pinjaman'];
        return Attribute::make(
            get: fn() => "Rp " . number_format($limit_pinjaman, 0, ',', '.'),
        );
    }
    protected function limitPinjaman(): Attribute
    {
        return Attribute::make(
            get: fn($value) => intval($value),
            set: fn($value) => floatval($value)
        );
    }
    /**
     * Get Ketua kelompok
     */
    public function ketuaName(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->users?->name ?? "Tidak ada")
        );
    }
    /**
     * Get Status kelompok
     */
    public function statusName(): Attribute
    {
        $status_name = $this->status_kelompok->name;
        return Attribute::make(
            get: fn() => Str::of($status_name)
        );
    }
}
