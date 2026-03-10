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
}
