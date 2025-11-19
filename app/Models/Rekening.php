<?php

namespace App\Models;

use App\Enums\Admin\CatatanPendanaan\EnumCatatanPendanaan;
use App\Enums\Admin\Rekening\EnumRekening;
use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_name',
        'formattted_inflow_data',
        'formatted_outflow_data',
        'formatted_transfer_data'
    ];

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
    protected function formattedInflowData(): Attribute
    {
        $today_start = now()->startOfDay();
        $today_end = now()->endOfDay();
        $inflow = TransaksiRekening::query()->search_by_column('tipe_transaksi', EnumTipeTransaksi::MASUK)
            ->whereBetween('created_at', [$today_start, $today_end])
            ->sum('nominal');
        return Attribute::make(
            get: fn() => "Rp " . number_format($inflow, 0, ',', '.') ?? 0
        );
    }
    protected function formattedOutflowData(): Attribute
    {
        $outflow = TransaksiRekening::query()->search_by_column('tipe_transaksi', EnumTipeTransaksi::KELUAR)->sum('nominal');
        return Attribute::make(
            get: fn() => "Rp " . number_format($outflow, 0, ',', '.') ?? 0
        );
    }
    protected function formattedTransferData(): Attribute
    {
        $transfer = TransaksiRekening::query()->search_by_column('tipe_transaksi', EnumTipeTransaksi::TRANSFER)->sum('nominal');
        return Attribute::make(
            get: fn() => "Rp " . number_format($transfer, 0, ',', '.') ?? 0
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
