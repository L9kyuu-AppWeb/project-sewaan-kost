<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananGalon extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_MENUNGGU_BAYAR = 'menunggu_bayar';
    const STATUS_DIPROSES = 'diproses';
    const STATUS_DIAMBIL = 'diambil';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesanan_galon';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_order_galon';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_penyewa',
        'id_kost',
        'id_galon_tipe',
        'foto_kosong',
        'foto_terisi',
        'status_galon',
        'total_bayar',
        'orderan_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_bayar' => 'decimal:2',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate orderan_id when creating
        static::creating(function ($order) {
            if (empty($order->orderan_id)) {
                $order->orderan_id = \App\PaymentHelper::generateGalonOrderId($order->id_penyewa);
            }
        });
    }

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
     * Get the galon type that was ordered.
     */
    public function galonType(): BelongsTo
    {
        return $this->belongsTo(GalonKatalog::class, 'id_galon_tipe', 'id_galon_tipe');
    }

    /**
     * Get the payment for this order.
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesan', 'id_order_galon')
            ->where('tipe_pembayaran', 'galon');
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalBayarAttribute(): string
    {
        return 'Rp ' . number_format($this->total_bayar, 0, ',', '.');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_galon) {
            self::STATUS_MENUNGGU_BAYAR => '#f093fb',
            self::STATUS_DIPROSES => '#4facfe',
            self::STATUS_DIAMBIL => '#ffa751',
            self::STATUS_SELESAI => '#43e97b',
            self::STATUS_DIBATALKAN => '#f5576c',
            default => '#666',
        };
    }

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_galon) {
            self::STATUS_MENUNGGU_BAYAR => 'Menunggu Pembayaran',
            self::STATUS_DIPROSES => 'Diproses',
            self::STATUS_DIAMBIL => 'Diambil',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return $this->status_galon === self::STATUS_MENUNGGU_BAYAR;
    }

    /**
     * Check if the order is pending payment.
     */
    public function isPendingPayment(): bool
    {
        return $this->status_galon === self::STATUS_MENUNGGU_BAYAR;
    }

    /**
     * Check if photo of filled gallon is required.
     */
    public function requiresPhotoTerisi(): bool
    {
        return in_array($this->status_galon, [self::STATUS_DIPROSES, self::STATUS_DIAMBIL]);
    }
}
