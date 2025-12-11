<?php

namespace App\Models;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Rekening\EnumRekening;
use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Rekening extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rekening';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ["name", "saldo"];

    /**
     * Scope a query rekening
     */
    public function scopeGet_rekening_akuntan($query)
    {
        return $query->where('name', EnumRekening::AKUNTAN);
    }
    public function scopeGet_rekening_pendanaan($query)
    {
        return $query->where('name', EnumRekening::PENGELOLA_DANA);
    }
    /**
     * Relationships
     */
    public function transaksi_rekening()
    {
        return $this->hasMany(TransaksiRekening::class, 'rekening_id');
    }

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_name',
        'formattted_inflow_data_akuntan',
        'formatted_outflow_data_akuntan',
    ];

    /**
     * Accessor
     * 
     */

    // Non Formatted
    protected function saldo(): Attribute
    {
        return Attribute::make(
            set: fn($value) => floatval($value)
        );
    }
    // Formatted
    protected function formattedInflowDataAkuntan(): Attribute
    {
        $rekening_akuntan = $this->get_rekening_akuntan()->first();
        $filtered_date = request()->get('created_at');
        $today_start = Carbon::parse($filtered_date ?? now())->startOfDay();
        $today_end = Carbon::parse($filtered_date ?? now())->endOfDay();
        $inflow = TransaksiRekening::query()->search_by_column('tipe_transaksi', EnumTipeTransaksi::MASUK)->where('rekening_id', $rekening_akuntan->id)
            ->whereBetween('created_at', [$today_start, $today_end])
            ->sum('nominal');
        return Attribute::make(
            get: fn() => "Rp " . number_format($inflow, 0, ',', '.') ?? 0
        );
    }
    protected function formattedOutflowDataAkuntan(): Attribute
    {
        $rekening_akuntan = $this->get_rekening_akuntan()->first();
        $filtered_date = request()->get('created_at');
        $today_start = Carbon::parse($filtered_date ?? now())->startOfDay();
        $today_end = Carbon::parse($filtered_date ?? now())->endOfDay();
        $outflow = TransaksiRekening::query()->search_by_column('tipe_transaksi', EnumTipeTransaksi::KELUAR)->where('rekening_id', $rekening_akuntan->id)
            ->whereBetween('created_at', [$today_start, $today_end])
            ->sum('nominal');
        return Attribute::make(
            get: fn() => "Rp " . number_format($outflow, 0, ',', '.') ?? 0
        );
    }
    protected function formattedSaldo(): Attribute
    {
        $saldo = $this->attributes['saldo'];
        return Attribute::make(
            get: fn() => "Rp " . number_format($saldo, 0, ',', '.') ?? 0
        );
    }
    protected function formattedName(): Attribute
    {
        $name = $this->name ?? "-";
        return Attribute::make(
            get: fn() => Str::of($name)->ucfirst()->replace("_", " ")
        );
    }
}
