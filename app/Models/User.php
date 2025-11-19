<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Admin\User\EnumRole;
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
    protected $appends = [
        'is_data_user_lengkap',
        'formatted_role',
        'formatted_tanggal_dibuat',
    ];

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
        'password',
        'nomor_telepon',
        'nomor_rekening',
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
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('nik', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%")
            ->orWhere('alamat', 'like', "%{$keyword}%")
            ->orWhere('nomor_telepon', 'like', "%{$keyword}%");
    }
    public function scopeSearch_by_column($query, $column, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        if (is_array($keyword)) {
            return $query->whereIn($column, $keyword);
        }
        return $query->where($column, $keyword);
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
        return $this->hasOne(Kelompok::class, 'users_id', 'id');
    }

    /**
     * Accessor
     * 
     */

    // Non formatted
    public function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value)
        );
    }
    public function resetPassword(string $new_password)
    {
        $this->password = $new_password;
        $this->save();
    }
    public function isDataUserLengkap(): Attribute
    {
        $data_user_lengkap = $this->nik && $this->alamat && $this->nomor_telepon && $this->nomor_rekening;
        return Attribute::make(
            get: fn() => $data_user_lengkap
        );
    }

    // Formatted
    protected function formattedRole(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::of($this->role->value)->ucfirst()->replace("_", " "),
        );
    }
    protected function formattedTanggalDibuat(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->format('d M Y | H:i') ?? "-",
        );
    }
}
