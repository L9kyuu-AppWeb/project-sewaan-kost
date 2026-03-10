<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pesan extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    const STATUS_PROSES_VERIFIKASI = 'proses_verifikasi';
    const STATUS_AKTIF = 'aktif';
    const STATUS_SELESAI = 'selesai';
    const STATUS_DIBATALKAN = 'dibatalkan';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesan';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_pesan';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_penyewa',
        'id_kamar',
        'tgl_pemesanan',
        'tgl_mulai',
        'durasi_bulan',
        'tgl_selesai',
        'total_harga',
        'status_pesan',
        'catatan',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'tgl_pemesanan' => 'datetime',
            'tgl_mulai' => 'date',
            'tgl_selesai' => 'date',
            'total_harga' => 'decimal:2',
            'durasi_bulan' => 'integer',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pesan) {
            if (!$pesan->tgl_pemesanan) {
                $pesan->tgl_pemesanan = now();
            }
            // Ensure durasi_bulan is an integer
            if (isset($pesan->durasi_bulan)) {
                $pesan->durasi_bulan = (int) $pesan->durasi_bulan;
            }
        });

        // Update kamar status when pesan status changes
        static::updated(function ($pesan) {
            if ($pesan->isDirty('status_pesan')) {
                $pesan->updateKamarStatus();
            }
        });

        // Update kamar status when pesan is deleted
        static::deleted(function ($pesan) {
            try {
                $kamar = $pesan->kamar;
                if ($kamar && $kamar->status_kamar === 'dipesan') {
                    $kamar->status_kamar = 'tersedia';
                    $kamar->save();
                }
            } catch (\Exception $e) {
                // Ignore errors on delete
            }
        });
    }

    /**
     * Get the penyewa (tenant) who made this booking.
     */
    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_penyewa', 'id_user');
    }

    /**
     * Get the kamar (room) that was booked.
     */
    public function kamar(): BelongsTo
    {
        return $this->belongsTo(Kamar::class, 'id_kamar', 'id_kamar');
    }

    /**
     * Get the payments for this booking.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Pembayaran::class, 'id_pesan', 'id_pesan');
    }

    /**
     * Get the latest payment for this booking.
     */
    public function latestPayment(): HasOne
    {
        return $this->hasOne(Pembayaran::class, 'id_pesan', 'id_pesan')
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
        return match($this->status_pesan) {
            self::STATUS_MENUNGGU_PEMBAYARAN => '#f093fb',
            self::STATUS_PROSES_VERIFIKASI => '#4facfe',
            self::STATUS_AKTIF => '#43e97b',
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
        return match($this->status_pesan) {
            self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
            self::STATUS_PROSES_VERIFIKASI => 'Proses Verifikasi',
            self::STATUS_AKTIF => 'Aktif',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DIBATALKAN => 'Dibatalkan',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if the booking is pending payment.
     */
    public function isPendingPayment(): bool
    {
        return $this->status_pesan === self::STATUS_MENUNGGU_PEMBAYARAN;
    }

    /**
     * Check if the booking is active.
     */
    public function isActive(): bool
    {
        return $this->status_pesan === self::STATUS_AKTIF;
    }

    /**
     * Check if the booking is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status_pesan === self::STATUS_SELESAI;
    }

    /**
     * Check if the booking is cancelled.
     */
    public function isCancelled(): bool
    {
        return $this->status_pesan === self::STATUS_DIBATALKAN;
    }

    /**
     * Update kamar status based on pesan status.
     */
    public function updateKamarStatus(): void
    {
        try {
            $kamar = $this->kamar;
            if (!$kamar) return;

            switch ($this->status_pesan) {
                case self::STATUS_MENUNGGU_PEMBAYARAN:
                case self::STATUS_PROSES_VERIFIKASI:
                    $kamar->status_kamar = 'dipesan';
                    break;
                case self::STATUS_AKTIF:
                    $kamar->status_kamar = 'terisi';
                    break;
                case self::STATUS_SELESAI:
                case self::STATUS_DIBATALKAN:
                    $kamar->status_kamar = 'tersedia';
                    break;
            }

            $kamar->save();
        } catch (\Exception $e) {
            // Log error but don't fail the operation
            \Log::error('Error updating kamar status: ' . $e->getMessage());
        }
    }

    /**
     * Calculate total price based on room price and duration.
     */
    public static function calculateTotalPrice(Kamar $kamar, int $durasiBulan): float
    {
        return $kamar->harga_per_bulan * $durasiBulan;
    }

    /**
     * Get remaining days until booking ends.
     */
    public function getRemainingDaysAttribute(): int
    {
        if (!$this->tgl_selesai) return 0;
        
        try {
            $today = now()->startOfDay();
            $endDate = \Carbon\Carbon::parse($this->tgl_selesai)->endOfDay();
            return max(0, $today->diffInDays($endDate, false));
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Check if booking has expired.
     */
    public function isExpired(): bool
    {
        if (!$this->tgl_selesai) return false;
        
        try {
            return \Carbon\Carbon::parse($this->tgl_selesai)->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }
}
