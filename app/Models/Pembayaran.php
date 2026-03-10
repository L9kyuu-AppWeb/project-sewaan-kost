<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    /**
     * Status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_DIVERIFIKASI = 'diverifikasi';
    const STATUS_DITOLAK = 'ditolak';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pembayarans';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_pembayaran';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_pesan',
        'jenis_pembayaran',
        'nama_bank',
        'nomor_rekening',
        'bukti_pembayaran',
        'jumlah_bayar',
        'tanggal_bayar',
        'status_verifikasi',
        'catatan_verifikasi',
        'verified_by',
        'verified_at',
        // Midtrans fields
        'order_id',
        'transaction_id',
        'payment_type',
        'transaction_status',
        'snap_token',
        'transaction_time',
        'settlement_time',
        'expire_time',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_bayar' => 'decimal:2',
            'tanggal_bayar' => 'date',
            'verified_at' => 'datetime',
            'transaction_time' => 'datetime',
            'settlement_time' => 'datetime',
            'expire_time' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::updated(function ($pembayaran) {
            // Update pesan status when payment is verified
            if ($pembayaran->isDirty('status_verifikasi') && 
                $pembayaran->status_verifikasi === self::STATUS_DIVERIFIKASI) {
                
                $pesan = $pembayaran->pesan;
                if ($pesan && $pesan->status_pesan === Pesan::STATUS_PROSES_VERIFIKASI) {
                    $pesan->status_pesan = Pesan::STATUS_AKTIF;
                    $pesan->save();
                }
            }
        });
    }

    /**
     * Get the pesan that owns this payment.
     */
    public function pesan(): BelongsTo
    {
        return $this->belongsTo(Pesan::class, 'id_pesan', 'id_pesan');
    }

    /**
     * Get the pemilik who verified this payment.
     */
    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by', 'id_user');
    }

    /**
     * Get the formatted amount.
     */
    public function getFormattedJumlahBayarAttribute(): string
    {
        return 'Rp ' . number_format($this->jumlah_bayar, 0, ',', '.');
    }

    /**
     * Get the status badge color.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_PENDING => '#f093fb',
            self::STATUS_DIVERIFIKASI => '#43e97b',
            self::STATUS_DITOLAK => '#f5576c',
            default => '#666',
        };
    }

    /**
     * Get the status label in Indonesian.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status_verifikasi) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_DIVERIFIKASI => 'Diverifikasi',
            self::STATUS_DITOLAK => 'Ditolak',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if payment is pending.
     */
    public function isPending(): bool
    {
        return $this->status_verifikasi === self::STATUS_PENDING;
    }

    /**
     * Check if payment is verified.
     */
    public function isVerified(): bool
    {
        return $this->status_verifikasi === self::STATUS_DIVERIFIKASI;
    }

    /**
     * Check if payment is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status_verifikasi === self::STATUS_DITOLAK;
    }

    /**
     * Get the payment type label.
     */
    public function getJenisPembayaranLabelAttribute(): string
    {
        return match($this->jenis_pembayaran) {
            'transfer_bank' => 'Transfer Bank',
            'ewallet' => 'E-Wallet',
            'tunai' => 'Tunai',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get Midtrans transaction status label.
     */
    public function getTransactionStatusLabelAttribute(): string
    {
        return match($this->transaction_status) {
            'pending' => 'Pending',
            'settlement' => 'Settlement (Berhasil)',
            'capture' => 'Capture',
            'cancel' => 'Dibatalkan',
            'deny' => 'Ditolak',
            'expire' => 'Expired',
            'refund' => 'Refund',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Check if payment is paid via Midtrans.
     */
    public function isPaidViaMidtrans(): bool
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Check if payment is expired.
     */
    public function isExpired(): bool
    {
        return $this->transaction_status === 'expire';
    }

    /**
     * Check if payment is cancelled.
     */
    public function isCancelled(): bool
    {
        return in_array($this->transaction_status, ['cancel', 'deny']);
    }

    /**
     * Generate Midtrans Snap Token.
     */
    public function generateSnapToken(): ?string
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');
            \Midtrans\Config::$isProduction = config('midtrans.is_production');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

            $params = [
                'transaction_details' => [
                    'order_id' => $this->order_id,
                    'gross_amount' => (int) $this->jumlah_bayar,
                ],
                'customer_details' => [
                    'first_name' => $this->pesan->penyewa->nama_lengkap,
                    'email' => $this->pesan->penyewa->email,
                    'phone' => $this->pesan->penyewa->no_hp,
                ],
            ];

            $snapToken = \Midtrans\Snap::getSnapToken($params);
            
            $this->snap_token = $snapToken;
            $this->save();

            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Error generating Snap token: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Handle Midtrans callback/notification.
     */
    public function handleCallback(array $notificationData): bool
    {
        try {
            \Midtrans\Config::$serverKey = config('midtrans.server_key');

            // Verify notification
            $statusCode = $notificationData['status_code'] ?? '';
            $transactionId = $notificationData['transaction_id'] ?? '';
            $order_id = $notificationData['order_id'] ?? '';
            $grossAmount = $notificationData['gross_amount'] ?? '';
            $transactionStatus = $notificationData['transaction_status'] ?? '';
            $signatureKey = $notificationData['signature_key'] ?? '';

            // Verify signature key
            $computedSignature = hash('sha512', $order_id . $statusCode . $grossAmount . config('midtrans.server_key'));
            if ($signatureKey !== $computedSignature) {
                \Log::error('Invalid Midtrans signature key');
                return false;
            }

            // Update payment data
            $this->transaction_id = $transactionId;
            $this->transaction_status = $transactionStatus;
            $this->payment_type = $notificationData['payment_type'] ?? null;

            if (isset($notificationData['transaction_time'])) {
                $this->transaction_time = $notificationData['transaction_time'];
            }

            if (isset($notificationData['settlement_time'])) {
                $this->settlement_time = $notificationData['settlement_time'];
            }

            if (isset($notificationData['expire_time'])) {
                $this->expire_time = $notificationData['expire_time'];
            }

            $this->save();

            // Handle based on transaction status
            return $this->processTransactionStatus($transactionStatus);
        } catch (\Exception $e) {
            \Log::error('Error handling Midtrans callback: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Process transaction status update.
     */
    private function processTransactionStatus(string $status): bool
    {
        $pesan = $this->pesan;
        if (!$pesan) return false;

        switch ($status) {
            case 'settlement':
            case 'capture':
                // Payment successful
                if ($pesan->status_pesan === \App\Models\Pesan::STATUS_PROSES_VERIFIKASI) {
                    $pesan->status_pesan = \App\Models\Pesan::STATUS_AKTIF;
                    $pesan->kamar->status_kamar = 'terisi';
                    $pesan->kamar->save();
                }
                $pesan->save();
                return true;

            case 'cancel':
            case 'deny':
            case 'expire':
                // Payment cancelled/expired
                $pesan->status_pesan = \App\Models\Pesan::STATUS_DIBATALKAN;
                $pesan->kamar->status_kamar = 'tersedia';
                $pesan->kamar->save();
                $pesan->save();
                return true;

            default:
                return false;
        }
    }
}
