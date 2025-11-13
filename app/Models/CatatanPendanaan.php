<?php

namespace App\Models;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tipe_catatan' => EnumCatatanPendanaan::class,
    ];
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
    protected $appends = ['formatted_jumlah_saldo', 'formatted_tipe_catatan', 'formatted_tanggal_catatan'];

    /**
     * Scope a query catatan pendanaan
     *
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('catatan', 'like', "%{$keyword}%")
            ->orWhere('jumlah_saldo', 'like', "%{$keyword}%")
            ->orWhere('tipe_catatan', 'like', "{$keyword}");
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
     * Accessor
     * 
     */
    public function formattedTipeCatatan(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->tipe_catatan->value)->ucfirst()
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
    public function formattedTanggalCatatan(): Attribute
    {
        $tanggal = $this->created_at ? Carbon::parse($this->created_at)->format("d M Y | H:i") : "-";
        return Attribute::make(
            get: fn() => $tanggal
        );
    }
}
