<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PesananMakananHeader extends Model
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
    protected $table = 'pesanan_makanan_header';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_pesanan_makanan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_penyewa',
        'id_kost',
        'total_harga',
        'total_item',
        'status_antar',
        'catatan',
        'orderan_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_harga' => 'decimal:2',
        'total_item' => 'integer',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-generate orderan_id when creating
        static::creating(function ($header) {
            if (empty($header->orderan_id)) {
                $header->orderan_id = \App\PaymentHelper::generateMakananOrderId($header->id_penyewa);
            }
        });

        // Update stock when order is cancelled
        static::updated(function ($header) {
            if ($header->isDirty('status_antar') && $header->status_antar === self::STATUS_DIBATALKAN) {
                // Restore stock
                foreach ($header->details as $detail) {
                    $detail->makanan->increment('stok', $detail->jumlah);
                }
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
     * Get all items in this order.
     */
    public function details(): HasMany
    {
        return $this->hasMany(PesananMakananDetail::class, 'id_pesanan_makanan', 'id_pesanan_makanan');
    }

    /**
     * Get the payment for this order.
     */
    public function pembayaran(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_pesan', 'id_pesanan_makanan')
            ->where('tipe_pembayaran', 'makanan');
    }

    /**
     * Get the latest payment for this order.
     */
    public function latestPembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesan', 'id_pesanan_makanan')
            ->where('tipe_pembayaran', 'makanan')
            ->latest('id_pembayaran');
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

    /**
     * Check if the order is pending payment.
     */
    public function isPendingPayment(): bool
    {
        return $this->status_antar === self::STATUS_MENUNGGU_BAYAR;
    }
}
