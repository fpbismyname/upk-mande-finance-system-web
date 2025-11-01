<?php

namespace App\Models\Status;

use App\Models\Kelompok;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StatusKelompok extends Model
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
    protected $table = 'status_kelompok';

    /**
     * Relationships
     * 
     */
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'status_id', 'id');
    }

    /**
     * Accessor
     * 
     */
    public function formattedName(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->name)->ucfirst()->replace("_", " ")
        );
    }
}
