<?php

namespace App\Models\Status;

use App\Models\JadwalPencairan;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StatusJadwalPencairan extends Model
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
    protected $table = 'status_jadwal_pencairan';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_name'];

    /**
     * Relationships : jadwal_pencairan
     * 
     */
    public function jadwal_pencairan()
    {
        return $this->hasMany(JadwalPencairan::class, 'status_id', 'id');
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
