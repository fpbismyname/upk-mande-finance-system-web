<?php

namespace App\Models;

use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanKeanggotaan extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pengajuan_keanggotaan';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nik',
        'ktp',
        'nama_lengkap',
        'alamat',
        'nomor_rekening',
        'nomor_telepon',
        'status',
        'users_id'
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => EnumStatusPengajuanKeanggotaan::class,
        'created_at' => 'datetime'
    ];
    /**
     * Relationships
     */
    public function users()
    {
        return $this->belongsTo(User::class, 'users_id');
    }
    /**
     * Scope
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('nik', 'like', "%{$keyword}%")
            ->orWhere('nama_lengkap', 'like', "%{$keyword}%")
            ->orWhere('nomor_rekening', 'like', "%{$keyword}%")
            ->orWhere('nomor_telepon', 'like', "%{$keyword}%")
            ->orWhere('alamat', 'like', "%{$keyword}%");
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
    /**
     * Appends
     */
    protected $appends = [
        'status_disetujui',
        'status_ditolak',
        'status_dibatalkan',
        'status_dalam_proses',
        'formatted_created_at',
    ];
    /**
     * Accessor
     */
    public function statusDisetujui(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPengajuanKeanggotaan::DISETUJUI
        );
    }
    public function statusDitolak(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPengajuanKeanggotaan::DITOLAK
        );
    }
    public function statusProsesPengajuan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPengajuanKeanggotaan::PROSES_PENGAJUAN
        );
    }
    public function statusDibatalkan(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->status === EnumStatusPengajuanKeanggotaan::DIBATALKAN
        );
    }
    public function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->translatedFormat('d F Y')
        );
    }
}
