<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Kost extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kost';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_kost';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_pemilik',
        'nama_kost',
        'alamat',
        'deskripsi',
        'fasilitas_umum',
        'peraturan',
        'foto_kost',
        'latitude',
        'longitude',
    ];

    /**
     * Get the pemilik (owner) of this kost.
     */
    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_pemilik', 'id_user');
    }

    /**
     * Get all rooms for this kost.
     */
    public function rooms(): HasMany
    {
        return $this->hasMany(Kamar::class, 'id_kost', 'id_kost');
    }

    /**
     * Get available rooms count.
     */
    public function getAvailableRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status_kamar', 'tersedia')->count();
    }

    /**
     * Get occupied rooms count.
     */
    public function getOccupiedRoomsCountAttribute(): int
    {
        return $this->rooms()->where('status_kamar', 'terisi')->count();
    }

    /**
     * Get all food menus for this kost.
     */
    public function foods(): HasMany
    {
        return $this->hasMany(Makanan::class, 'id_kost', 'id_kost');
    }

    /**
     * Get available food menus for this kost.
     */
    public function availableFoods(): HasMany
    {
        return $this->foods()->where('is_available', true)->where('stok', '>', 0);
    }

    /**
     * Get all food orders for this kost.
     */
    public function foodOrders(): HasMany
    {
        return $this->hasMany(PesananMakanan::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all food order headers for this kost (new cart system).
     */
    public function foodOrderHeaders(): HasMany
    {
        return $this->hasMany(PesananMakananHeader::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all galon catalog types for this kost.
     */
    public function galonTypes(): HasMany
    {
        return $this->hasMany(GalonKatalog::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all galon orders for this kost.
     */
    public function galonOrders(): HasMany
    {
        return $this->hasMany(PesananGalon::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all laundry catalog types for this kost.
     */
    public function laundryTypes(): HasMany
    {
        return $this->hasMany(LaundryKatalog::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all laundry orders for this kost.
     */
    public function laundryOrders(): HasMany
    {
        return $this->hasMany(PesananLaundry::class, 'id_kost', 'id_kost');
    }
}
