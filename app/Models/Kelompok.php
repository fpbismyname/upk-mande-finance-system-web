<?php

namespace App\Models;

use App\Enum\Admin\Status\EnumStatusKelompok;
use Illuminate\Database\Eloquent\Builder;
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
    protected $appends = ['ketua_name', 'formatted_status', 'formatted_name'];

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
    public function scopeFilter($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('limit_pinjaman', 'like', "%{$keyword}%")
            ->orWhereHas('users', fn($qr) => $qr->where('name', 'like', "%{$keyword}%"))
        ;
    }
    public function scopeFilterStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
    public function anggota_kelompok()
    {
        return $this->hasMany(AnggotaKelompok::class, 'kelompok_id');
    }
    public function pengajuan_pinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'kelompok_id', 'id');
    }
    public function pinjaman_kelompok()
    {
        return $this->hasMany(PinjamanKelompok::class, 'kelompok_id', 'id');
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
    protected function formattedName(): Attribute
    {
        $kelompok_name = $this->attributes['name'];
        return Attribute::make(
            get: fn() => Str::of($kelompok_name)->ucfirst(),
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
}
