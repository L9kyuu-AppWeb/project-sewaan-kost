<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nama_lengkap',
        'email',
        'password',
        'no_hp',
        'role',
        'nik',
        'foto_profil',
        'alamat_asal',
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
            'password' => 'hashed',
        ];
    }

    /**
     * Get all kosts owned by this user.
     */
    public function kosts(): HasMany
    {
        return $this->hasMany(Kost::class, 'id_pemilik', 'id_user');
    }

    /**
     * Get all bookings made by this user (for tenants).
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesan::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get all food orders made by this user (for tenants).
     */
    public function foodOrders(): HasMany
    {
        return $this->hasMany(PesananMakanan::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get all food order headers made by this user (new cart system).
     */
    public function foodOrderHeaders(): HasMany
    {
        return $this->hasMany(PesananMakananHeader::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get all galon orders made by this user.
     */
    public function galonOrders(): HasMany
    {
        return $this->hasMany(PesananGalon::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get all laundry orders made by this user.
     */
    public function laundryOrders(): HasMany
    {
        return $this->hasMany(PesananLaundry::class, 'id_penyewa', 'id_user');
    }
}
