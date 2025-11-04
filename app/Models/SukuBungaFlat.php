<?php

namespace App\Models;

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
     * @var array
     */
    protected $fillable = ['jumlah'];
}
