<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PesananLaundry extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_MENUNGGU_JEMPUT = 'menunggu_jemput';
    const STATUS_MENUNGGU_BAYAR = 'menunggu_bayar';
    const STATUS_SEDANG_DICUCI = 'sedang_dicuci';
    const STATUS_Siap_ANTAR = 'siap_antar';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesanan_laundry';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_order_laundry';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_penyewa',
        'id_kost',
        'id_laundry_tipe',
        'berat_kg',
        'total_harga',
        'foto_awal',
        'foto_selesai',
        'tgl_selesai_estimasi',
        'tgl_selesai_aktual',
        'status_laundry',
        'orderan_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'berat_kg' => 'decimal:2',
        'total_harga' => 'decimal:2',
        'tgl_selesai_estimasi' => 'date',
        'tgl_selesai_aktual' => 'datetime',
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
                $order->orderan_id = \App\PaymentHelper::generateLaundryOrderId($order->id_penyewa);
            }
        });

        // Auto-set tgl_selesai_aktual when foto_selesai is uploaded
        static::updating(function ($order) {
            if ($order->isDirty('foto_selesai') && $order->foto_selesai !== null) {
                $order->tgl_selesai_aktual = now();
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
     * Get the laundry type that was ordered.
     */
    public function laundryType(): BelongsTo
    {
        return $this->belongsTo(LaundryKatalog::class, 'id_laundry_tipe', 'id_laundry_tipe');
    }

    /**
     * Get the payment for this order.
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_pesan', 'id_order_laundry')
            ->where('tipe_pembayaran', 'laundry');
    }

    /**
     * Get the formatted total price.
     */
    public function getFormattedTotalHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga ?? 0, 0, ',', '.');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_laundry) {
            self::STATUS_MENUNGGU_JEMPUT => '#970747',
            self::STATUS_MENUNGGU_BAYAR => '#f093fb',
            self::STATUS_SEDANG_DICUCI => '#4facfe',
            self::STATUS_Siap_ANTAR => '#ffa751',
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
        return match($this->status_laundry) {
            self::STATUS_MENUNGGU_JEMPUT => 'Menunggu Jemput',
            self::STATUS_MENUNGGU_BAYAR => 'Menunggu Pembayaran',
            self::STATUS_SEDANG_DICUCI => 'Sedang Dicuci',
            self::STATUS_Siap_ANTAR => 'Siap Antar',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if the order is late (terlambat).
     */
    public function isLate(): bool
    {
        if (!$this->tgl_selesai_estimasi || !$this->tgl_selesai_aktual) {
            return false;
        }

        return $this->tgl_selesai_aktual->endOfDay() > $this->tgl_selesai_estimasi->endOfDay();
    }

    /**
     * Get days late (negative if on time).
     */
    public function getDaysLateAttribute(): int
    {
        if (!$this->tgl_selesai_estimasi || !$this->tgl_selesai_aktual) {
            return 0;
        }

        return $this->tgl_selesai_estimasi->diffInDays($this->tgl_selesai_aktual, false);
    }

    /**
     * Check if can update weight (owner can weigh).
     */
    public function canUpdateWeight(): bool
    {
        return in_array($this->status_laundry, [self::STATUS_MENUNGGU_JEMPUT]);
    }

    /**
     * Check if can set estimation date.
     */
    public function canSetEstimationDate(): bool
    {
        // Can set estimation if:
        // 1. Status is sedang_dicuci (payment settled)
        // 2. Estimation date is not already set
        return $this->status_laundry === self::STATUS_SEDANG_DICUCI && $this->tgl_selesai_estimasi === null;
    }

    /**
     * Check if can upload finished photo.
     */
    public function canUploadFinishedPhoto(): bool
    {
        return $this->status_laundry === self::STATUS_SEDANG_DICUCI && $this->tgl_selesai_estimasi !== null;
    }

    /**
     * Check if the order can be cancelled.
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status_laundry, [self::STATUS_MENUNGGU_JEMPUT, self::STATUS_MENUNGGU_BAYAR]);
    }

    /**
     * Check if payment is pending.
     */
    public function isPendingPayment(): bool
    {
        return $this->status_laundry === self::STATUS_MENUNGGU_BAYAR;
    }
}
