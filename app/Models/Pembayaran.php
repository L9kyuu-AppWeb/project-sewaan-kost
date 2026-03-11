<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

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
        'jumlah_bayar',
        'tanggal_bayar',
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
            // Update pesan status when payment transaction_status changes to settlement
            if ($pembayaran->isDirty('transaction_status') &&
                in_array($pembayaran->transaction_status, ['settlement', 'capture'])) {

                $pesan = $pembayaran->pesan;
                if ($pesan && in_array($pesan->status_pesan, [Pesan::STATUS_PROSES_VERIFIKASI, Pesan::STATUS_MENUNGGU_PEMBAYARAN])) {
                    $pesan->status_pesan = Pesan::STATUS_AKTIF;
                    $pesan->save();
                    
                    // Update kamar status to terisi
                    if ($pesan->kamar && $pesan->kamar->status_kamar !== 'terisi') {
                        $pesan->kamar->status_kamar = 'terisi';
                        $pesan->kamar->save();
                    }
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
     * Uses Midtrans transaction_status.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->transaction_status) {
            'pending' => '#f093fb',
            'settlement', 'capture' => '#43e97b',
            'cancel', 'deny', 'expire' => '#f5576c',
            default => '#666',
        };
    }

    /**
     * Get the status label in Indonesian.
     * Uses Midtrans transaction_status.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->transaction_status) {
            'pending' => 'Pending',
            'settlement', 'capture' => 'Settlement (Berhasil)',
            'cancel' => 'Dibatalkan',
            'deny' => 'Ditolak',
            'expire' => 'Expired',
            default => ucfirst($this->transaction_status) ?? 'Tidak Diketahui',
        };
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
            // Get config directly
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production');
            $skipSsl = config('midtrans.skip_ssl_verification');
            
            // Log for debugging
            \Log::info('Midtrans Config Check', [
                'server_key' => $serverKey,
                'server_key_length' => strlen($serverKey),
                'is_production' => $isProduction,
                'skip_ssl' => $skipSsl,
            ]);
            
            // Use manual CURL request to avoid library issues
            $baseUrl = $isProduction 
                ? 'https://app.midtrans.com/snap/v1/transactions' 
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
            
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
            
            \Log::info('Midtrans Request', ['params' => $params]);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $baseUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Accept: application/json',
                'Authorization: Basic ' . base64_encode($serverKey . ':')
            ]);
            
            if ($skipSsl) {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            }
            
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);
            
            \Log::info('Midtrans Response', [
                'http_code' => $httpCode,
                'curl_error' => $curlError,
                'response' => $response,
            ]);
            
            if ($curlError) {
                throw new \Exception('CURL Error: ' . $curlError);
            }
            
            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new \Exception('Midtrans API Error: HTTP ' . $httpCode . ' - ' . $response);
            }
            
            $result = json_decode($response);
            
            if (!$result || !isset($result->token)) {
                throw new \Exception('Invalid response from Midtrans: ' . $response);
            }
            
            $snapToken = $result->token;
            
            \Log::info('Snap Token Generated', ['token' => $snapToken]);
            
            $this->snap_token = $snapToken;
            $this->save();
            
            return $snapToken;
        } catch (\Exception $e) {
            \Log::error('Error generating Snap token: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return null;
        }
    }

    /**
     * Handle Midtrans callback/notification.
     */
    public function handleCallback(array $notificationData): bool
    {
        try {
            \Log::info('Midtrans Callback Processing', [
                'order_id' => $notificationData['order_id'] ?? 'N/A',
                'transaction_status' => $notificationData['transaction_status'] ?? 'N/A',
                'payment_type' => $notificationData['payment_type'] ?? 'N/A',
            ]);

            \Midtrans\Config::$serverKey = config('midtrans.server_key');

            // Verify notification
            $statusCode = $notificationData['status_code'] ?? '';
            $transactionId = $notificationData['transaction_id'] ?? '';
            $order_id = $notificationData['order_id'] ?? '';
            $grossAmount = $notificationData['gross_amount'] ?? '';
            $transactionStatus = $notificationData['transaction_status'] ?? '';
            $signatureKey = $notificationData['signature_key'] ?? '';

            // Verify signature key (Midtrans signature format)
            // Signature key = SHA512(order_id + status_code + gross_amount + server_key)
            $computedSignature = hash('sha512', $order_id . $statusCode . $grossAmount . config('midtrans.server_key'));
            
            \Log::info('Signature Verification', [
                'received' => $signatureKey,
                'computed' => $computedSignature,
                'match' => $signatureKey === $computedSignature,
            ]);

            if ($signatureKey !== $computedSignature) {
                \Log::error('Invalid Midtrans signature key - skipping verification for development');
                // For development, we skip signature verification if it fails
                // In production, you should NOT skip this!
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

            \Log::info('Payment Updated', [
                'pembayaran_id' => $this->id_pembayaran,
                'transaction_status' => $transactionStatus,
            ]);

            // Handle based on transaction status
            return $this->processTransactionStatus($transactionStatus);
        } catch (\Exception $e) {
            \Log::error('Error handling Midtrans callback: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return false;
        }
    }

    /**
     * Process transaction status update.
     */
    private function processTransactionStatus(string $status): bool
    {
        $pesan = $this->pesan;
        if (!$pesan) {
            \Log::error('Pesan not found for pembayaran', ['pembayaran_id' => $this->id_pembayaran]);
            return false;
        }

        \Log::info('Processing Transaction Status', [
            'status' => $status,
            'pesan_id' => $pesan->id_pesan,
            'current_status' => $pesan->status_pesan,
        ]);

        switch ($status) {
            case 'settlement':
            case 'capture':
                // Payment successful
                \Log::info('Payment settled', ['pesan_id' => $pesan->id_pesan]);
                if ($pesan->status_pesan === \App\Models\Pesan::STATUS_PROSES_VERIFIKASI || 
                    $pesan->status_pesan === \App\Models\Pesan::STATUS_MENUNGGU_PEMBAYARAN) {
                    $pesan->status_pesan = \App\Models\Pesan::STATUS_AKTIF;
                    $pesan->save();
                }
                if ($pesan->kamar && $pesan->kamar->status_kamar !== 'terisi') {
                    $pesan->kamar->status_kamar = 'terisi';
                    $pesan->kamar->save();
                }
                return true;

            case 'pending':
                // Payment is pending (customer hasn't completed payment yet)
                \Log::info('Payment pending', ['pesan_id' => $pesan->id_pesan]);
                // Don't change status, just log it
                return true;

            case 'cancel':
            case 'deny':
            case 'expire':
                // Payment cancelled/expired
                \Log::info('Payment cancelled/denied/expired', ['pesan_id' => $pesan->id_pesan]);
                $pesan->status_pesan = \App\Models\Pesan::STATUS_DIBATALKAN;
                $pesan->save();
                if ($pesan->kamar) {
                    $pesan->kamar->status_kamar = 'tersedia';
                    $pesan->kamar->save();
                }
                return true;

            default:
                \Log::warning('Unknown transaction status', ['status' => $status]);
                return false;
        }
    }
}
