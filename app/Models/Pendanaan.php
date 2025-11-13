<?php

namespace App\Models;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Services\Admin\CatatanPendanaan\CatatanPendanaanService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Pendanaan extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ["saldo"];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pendanaan';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_saldo', 'inflow_data', 'outflow_data'];

    /**
     * Accessor
     * 
     */

    protected function formattedSaldo(): Attribute
    {
        $saldo = $this->attributes['saldo'];
        return Attribute::make(
            get: fn() => "Rp " . number_format($saldo, 0, ',', '.') ?? 0
        );
    }
    protected function saldo(): Attribute
    {
        return Attribute::make(
            set: fn($value) => floatval($value)
        );
    }
    protected function inflowData(): Attribute
    {
        $inflow = CatatanPendanaan::query()->where('tipe_catatan', EnumCatatanPendanaan::INFLOW)->sum('jumlah_saldo');
        return Attribute::make(
            get: fn() => number_format($inflow, 0, ',', '.') ?? 0
        );
    }
    protected function outflowData(): Attribute
    {
        $outflow = CatatanPendanaan::query()->where('tipe_catatan', EnumCatatanPendanaan::OUTFLOW)->sum('jumlah_saldo');
        return Attribute::make(
            get: fn() => number_format($outflow, 0, ',', '.') ?? 0
        );
    }
}
