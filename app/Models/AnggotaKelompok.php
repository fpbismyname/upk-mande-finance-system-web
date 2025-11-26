<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelompok extends Model
{
    use HasFactory;

    protected $table = 'anggota_kelompok';

    protected $fillable = ['nik', 'name', 'alamat', 'nomor_telepon', 'kelompok_id'];

    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }

    public function scopeSearch($query, $keyword)
    {
        if (!$keyword) {
            return $query;
        }
        return $query->where('nik', 'like', "%{$keyword}%")
            ->orWhere('name', 'like', "%{$keyword}%")
            ->orWhere('alamat', 'like', "%{$keyword}%")
            ->orWhere('nomor_telepon', 'like', "%{$keyword}%");
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

    // public static function booted()
    // {
    //     static::creating(function () {
    //         if (self::get()->count() === 100) {
    //             return false;
    //         }
    //     });
    // }
}
