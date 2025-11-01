<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Roles extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['formatted_name'];

    /**
     * Relationships.
     * 
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Accessor
     * 
     */

    // Get all roles without admin user
    public function get_roles_without_admin_role()
    {
        return $this->whereNot('name', 'admin')->get();
    }
    // Get formatted role name
    public function formattedName(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->name)->ucfirst()->replace("_", " ")
        );
    }
}
