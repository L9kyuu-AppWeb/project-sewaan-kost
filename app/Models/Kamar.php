<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Kamar extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kamar';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_kamar';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kost',
        'nomor_kamar',
        'lantai',
        'harga_per_bulan',
        'status_kamar',
        'ukuran_kamar',
        'fasilitas_kamar',
        'foto_kamar',
    ];

    /**
     * Get the kost that owns this room.
     */
    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all bookings for this room.
     */
    public function pesanans(): HasMany
    {
        return $this->hasMany(Pesan::class, 'id_kamar', 'id_kamar');
    }

    /**
     * Get the active booking for this room.
     */
    public function activePesanan(): HasOne
    {
        return $this->hasOne(Pesan::class, 'id_kamar', 'id_kamar')
            ->whereIn('status_pesan', [Pesan::STATUS_AKTIF, Pesan::STATUS_PROSES_VERIFIKASI])
            ->latest('id_pesan');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_bulan, 0, ',', '.');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_kamar) {
            'tersedia' => '#43e97b',
            'dipesan' => '#f093fb',
            'terisi' => '#f5576c',
            default => '#666',
        };
    }

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_kamar) {
            'tersedia' => 'Tersedia',
            'dipesan' => 'Dipesan',
            'terisi' => 'Terisi',
            default => 'Tidak Diketahui',
        };
    }
}
