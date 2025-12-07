<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Admin\Status\EnumStatusPengajuanKeanggotaan;
use App\Enums\Admin\User\EnumRole;
use Illuminate\Database\Eloquent\Builder;
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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'role',
        'password',
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
            'role' => EnumRole::class,
            'created_at' => 'datetime'
        ];
    }

    /**
     *  Relationships
     * 
     */
    public function kelompok()
    {
        return $this->hasOne(Kelompok::class, 'users_id');
    }
    public function pengajuan_keanggotaan()
    {
        return $this->hasMany(PengajuanKeanggotaan::class, 'users_id');
    }

    public function pengajuan_keanggotaan_disetujui()
    {
        return $this->hasMany(PengajuanKeanggotaan::class, 'users_id')
            ->where('status', EnumStatusPengajuanKeanggotaan::DISETUJUI);
    }

    /**
     * Scope
     */
    public function scopeSearch($query, $keyword)
    {
        if (is_null($keyword) || $keyword === '') {
            return $query;
        }
        return $query->where('name', 'like', "%{$keyword}%")
            ->orWhere('email', 'like', "%{$keyword}%");
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
    public function scopeDoesntHaveKelompok(Builder $query, string $id = null)
    {
        return $query->whereDoesntHave('kelompok', function ($q) use ($id) {
            if ($id) {
                $q->where('id', $id);
            }
        });
    }
    public function scopeClientUsers(Builder $query)
    {
        return $query->whereIn('role', EnumRole::getValues('list_client_role'));
    }

    /**
     * Appends
     */
    protected $appends = [
        'formatted_created_at',
        'is_data_user_lengkap',
        'role_anggota',
        'dapat_mengajukan_keanggotaan',
    ];
    /**
     * Accessor
     */
    public function formattedCreatedAt(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->translatedFormat('d F Y')
        );
    }
    public function isDataUserLengkap(): Attribute
    {
        $data_keanggotaan = $this->pengajuan_keanggotaan_disetujui();
        $data_berisi_dokumen_lengkap = $data_keanggotaan->first()?->ktp != null
            && $data_keanggotaan->first()?->nik != null
            && $data_keanggotaan->first()?->nomor_rekening != null
            && $data_keanggotaan->first()?->nomor_telepon != null;
        $data_keanggotaan_lengkap = $data_keanggotaan->exists() && $data_berisi_dokumen_lengkap;
        return Attribute::make(
            get: fn() => $data_keanggotaan_lengkap
        );
    }
    public function roleAnggota(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->role === EnumRole::ANGGOTA
        );
    }
    public function dapatMengajukanKeanggotaan(): Attribute
    {
        $pengajuan_dalam_proses = $this->pengajuan_keanggotaan()->where('status', EnumStatusPengajuanKeanggotaan::PROSES_PENGAJUAN)->exists();
        $pengajuan_disetujui = $this->pengajuan_keanggotaan_disetujui()->exists();
        return Attribute::make(
            get: fn() => $pengajuan_dalam_proses != true && $pengajuan_disetujui != true
        );
    }
}
