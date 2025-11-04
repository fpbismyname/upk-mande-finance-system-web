<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\Admin\User\EnumRole;
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
    protected $appends = ['formatted_role'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nik',
        'name',
        'email',
        'role',
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
     * @return array<string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => EnumRole::class
        ];
    }

    /**
     * Scope a query to filter users
     *
     */
    public function scopeFilter($query, $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('nik', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('alamat', 'like', "%{$keyword}%")
            ->orWhere('nomor_telepon', 'like', "%{$keyword}%");
    }
    public function scopeFilterRole($query, $keyword)
    {
        return $query->where('role', 'like', $keyword);
    }
    public function scopeDoesntHaveKelompok($query, $except = null)
    {
        return $query
            ->where(function ($q) use ($except) {
                $q->whereDoesntHave('kelompok');

                if ($except) {
                    $q->orWhereIn('id', (array) $except);
                }
            })
            ->where('role', EnumRole::ANGGOTA);
    }
    /**
     *  Relationships
     * 
     */
    public function kelompok()
    {
        return $this->hasMany(Kelompok::class, 'users_id', 'id');
    }

    /**
     * Accessor
     * 
     */

    /**
     * Get user Role
     */
    protected function formattedRole(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->role->value)->ucfirst()->replace("_", " "),
        );
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
}
