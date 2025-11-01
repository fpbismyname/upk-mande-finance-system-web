<?php

namespace App\Models\Status;

use Illuminate\Database\Eloquent\Model;

class StatusPinjaman extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'status_pinjaman';
}
