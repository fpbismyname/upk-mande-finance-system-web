<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnggotaKelompok extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nik', 'name', 'alamat', 'nomor_telepon', 'kelompok_id'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'anggota_kelompok';
    /**
     * Relationships : kelompok
     */
    public function kelompok()
    {
        return $this->belongsTo(Kelompok::class, 'kelompok_id');
    }
}
