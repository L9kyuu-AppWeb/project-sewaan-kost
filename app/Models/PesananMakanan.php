<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananMakanan extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_MENUNGGU_BAYAR = 'menunggu_bayar';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_DIKIRIM = 'dikirim';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesanan_makan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_order_makan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_penyewa',
        'id_kost',
        'id_makanan',
        'jumlah',
        'total_harga',
        'status_antar',
        'catatan',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_harga' => 'decimal:2',
        'jumlah' => 'integer',
    ];

    /**
     * Get the penyewa (tenant) who made this order.
     */
    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get the kost associated with this order.
     */
    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    /**
     * Get the makanan (food) that was ordered.
     */
    public function makanan(): BelongsTo
    {
        return $this->belongsTo(Makanan::class, 'id_makanan', 'id_makanan');
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_antar) {
            self::STATUS_MENUNGGU_BAYAR => '#f093fb',
            self::STATUS_DIPROSES => '#4facfe',
            self::STATUS_DIKIRIM => '#43e97b',
            self::STATUS_SELESAI => '#666',
            self::STATUS_DIBATALKAN => '#f5576c',
            default => '#666',
        };
    }

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_antar) {
            self::STATUS_MENUNGGU_BAYAR => 'Menunggu Pembayaran',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_DIKIRIM => 'Dikirim',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if the order is pending payment.
     */
    public function isPendingPayment(): bool
    {
        return $this->status_antar === self::STATUS_MENUNGGU_BAYAR;
    }

    /**
     * Check if the order is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status_antar === self::STATUS_SELESAI;
    }

    /**
     * Check if the order is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status_antar === self::STATUS_DIBATALKAN;
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status_antar === self::STATUS_MENUNGGU_BAYAR;
    }

    /**
     * Check if the order can be marked as complete.
     */
    public function canBeCompleted(): bool
    {
        return in_array($this->status_antar, [self::STATUS_DIKIRIM, self::STATUS_DIPROSES]);
    }
}
