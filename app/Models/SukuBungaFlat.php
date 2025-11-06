<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class SukuBungaFlat extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'suku_bunga_flat';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = ['jumlah'];
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_jumlah'];

    /**
     * Accessor
     * 
     */
    public function formattedJumlah(): Attribute
    {
        $sukubunga = $this->jumlah ?? 0;
        return Attribute::make(
            get: fn() => "{$sukubunga}%"
        );
    }
}
