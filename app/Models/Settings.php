<?php

namespace App\Models;

use App\Enums\Admin\Settings\EnumSettingGroup;
use App\Enums\Admin\Settings\EnumSettingKeys;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Settings extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'value', 'type', 'group'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['bunga_pinjaman', 'formatted_group'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'key' => EnumSettingKeys::class,
        'group' => EnumSettingGroup::class,
    ];

    /**
     * Scope a querykey
     */
    public function scopeGetKeySetting($query, EnumSettingKeys $key)
    {
        return $query->where('key', $key);
    }
    public function scopeGetGroupSetting($query, EnumSettingGroup $key)
    {
        return $query->where('group', $key);
    }

    /**
     * Accessor
     */
    public function formattedGroup(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->group)->ucfirst()->replace("_", " ")
        );
    }
}
