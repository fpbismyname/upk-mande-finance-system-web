<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['role_name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'role_id',
        'password',
        'alamat',
        'nomor_telepon',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     *  Relationships
     * 
     */
    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id');
    }
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'ketua_id', 'id');
    }

    /**
     * Accessor
     * 
     */

    /**
     * Get user Role
     */
    protected function roleName(): Attribute
    {
        $current_role = Str::of($this->roles?->name)->replace("_", " ");
        return Attribute::make(
            get: fn() => $current_role,
        );
    }

    /**
     * Get All Roles
     */
    public static function get_all_roles()
    {
        $roles = Roles::all();
        return $roles;
    }

    /**
     * Reset pass
     */
    public function setPasswordAttribute(string $new_password)
    {
        $this->attributes['password'] = Hash::make($new_password);
    }
    public function resetPassword(string $new_password)
    {
        $this->password = $new_password;
        $this->save();
    }

    /**
     * Get user by roles
     */
    public static function get_users_for_kelompok($role_name, $without_user_has_kelompoks = true, $user = null)
    {
        $model = User::with(['roles']);
        $users = $model->whereRelation('roles', 'name', $role_name);
        return $without_user_has_kelompoks ? $users->whereDoesntHave('kelompok')->orWhere('name', $user) : $users;
    }
}
