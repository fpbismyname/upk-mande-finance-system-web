<?php

namespace App\Models;

use App\Enums\Admin\Rekening\EnumTipeTransaksi;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class TransaksiRekening extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transaksi_rekening';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'rekening_id',
        'nominal',
        'keterangan',
        'tipe_transaksi',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'tipe_transaksi' => EnumTipeTransaksi::class,
    ];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'formatted_tipe_transaksi',
        'formatted_nominal',
        'formatted_tanggal_transaksi',
        'formatted_name'
    ];

    /**
     * Scope a query catatan pendanaan
     *
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('nominal', 'like', "%{$keyword}%")
            ->orWhere('keterangan', 'like', "%{$keyword}%");
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
    public function scopeSearch_by_date($query, $column, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        if (is_array($keyword)) {
            return $query->whereIn($column, $keyword);
        }
        $start = Carbon::parse($keyword)->startOfDay();
        $end = Carbon::parse($keyword)->endOfDay();

        return $query->whereBetween($column, [$start, $end]);
    }
    /**
     * Relationships
     * 
     */
    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'rekening_id');
    }

    /**
     * Accessor
     * 
     */
    // Non Formatted
    public function nominal(): Attribute
    {
        return Attribute::make(
            set: fn($value) => floatval($value)
        );
    }

    // Formatted
    public function formattedTipeTransaksi(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->tipe_transaksi->value)->ucfirst()
        );
    }
    public function formattedNominal(): Attribute
    {
        return Attribute::make(
            get: fn() => "Rp " . number_format($this->nominal, 0, '.', ',')
        );
    }
    public function formattedTanggalTransaksi(): Attribute
    {
        $tanggal = $this->created_at ? Carbon::parse($this->created_at)->format("d M Y | H:i") : "-";
        return Attribute::make(
            get: fn() => $tanggal
        );
    }
}
