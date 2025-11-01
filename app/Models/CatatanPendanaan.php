<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CatatanPendanaan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['catatan', 'jumlah_saldo', 'tipe_catatan'];
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'catatan_pendanaan';
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_jumlah_saldo', 'formatted_tipe_catatan'];

    /**
     * Accessor
     * 
     */
    public function tipeCatatan(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Str::of($value)
        );
    }
    public function formattedTipeCatatan(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->tipe_catatan)->ucfirst()
        );
    }
    public function formattedJumlahSaldo(): Attribute
    {
        return Attribute::make(
            get: fn() => "Rp " . number_format($this->jumlah_saldo, 0, '.', ',')
        );
    }
    public function jumlahSaldo(): Attribute
    {
        return Attribute::make(
            set: fn($value) => floatval($value)
        );
    }
}
