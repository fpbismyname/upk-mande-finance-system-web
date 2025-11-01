<?php

namespace App\Models\Status;

use App\Models\PengajuanPinjaman;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StatusPengajuanPinjaman extends Model
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
    protected $table = 'status_pengajuan_pinjaman';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_name'];

    /**
     *  Relationships : pengajuan_pinjaman
     */
    public function pengajuan_pinjaman()
    {
        return $this->hasMany(PengajuanPinjaman::class, 'status_id', 'id');
    }

    /**
     *  Accessor
     */
    // get formatted status name
    public function formattedName(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->name)->replace("_", " ")->ucfirst()
        );
    }
}
