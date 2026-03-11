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
        'tipe_pembayaran',
        'orderan_id',
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

        // Auto-generate orderan_id when creating
        static::creating(function ($pembayaran) {
            if (empty($pembayaran->orderan_id)) {
                $pembayaran->orderan_id = $pembayaran->generateOrderanId();
            }
        });

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
     * Generate unique orderan_id based on payment type.
     * Format: {TYPE}-{id_pesan/order_id}-{timestamp}{suffix}
     * Examples: KAMAR-1-1773182971M, MAKANAN-1-1773182972, GALON-1-1773182973, LAUNDRY-1-1773182971
     */
    public function generateOrderanId(): string
    {
        $typePrefix = strtoupper($this->tipe_pembayaran ?? 'kamar');
        $referenceId = $this->id_pesan ?? 0;
        $timestamp = time();
        $suffix = '';

        // Add suffix based on type
        if ($typePrefix === 'KAMAR') {
            $suffix = 'M';
        }

        return "{$typePrefix}-{$referenceId}-{$timestamp}{$suffix}";
    }

    /**
     * Get the pesan that owns this payment (for kamar payments).
     */
    public function pesan(): BelongsTo
    {
        return $this->belongsTo(Pesan::class, 'id_pesan', 'id_pesan');
    }

    /**
     * Get the pesanan makanan that owns this payment (for food payments).
     * Uses id_pesan field to store id_pesanan_makanan for food orders.
     */
    public function pesananMakanan(): BelongsTo
    {
        return $this->belongsTo(PesananMakananHeader::class, 'id_pesan', 'id_pesanan_makanan');
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
     * Get the order reference based on payment type.
     */
    public function getOrderReferenceAttribute(): string
    {
        return match($this->tipe_pembayaran) {
            'kamar' => 'Kamar #' . ($this->pesan?->id_pesan ?? '-'),
            'makanan' => 'Makanan #' . ($this->pesananMakanan?->id_order_makan ?? '-'),
            'galon' => 'Galon #' . ($this->orderan_id ?? '-'),
            'laundry' => 'Laundry #' . ($this->orderan_id ?? '-'),
            default => '#'. ($this->orderan_id ?? '-'),
        };
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
     * Get the payment type label.
     */
    public function getTipePembayaranLabelAttribute(): string
    {
        return match($this->tipe_pembayaran) {
            'kamar' => 'Kamar',
            'makanan' => 'Makanan',
            'galon' => 'Galon',
            'laundry' => 'Laundry',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get the payment type icon.
     */
    public function getTipePembayaranIconAttribute(): string
    {
        return match($this->tipe_pembayaran) {
            'kamar' => '🏠',
            'makanan' => '🍽️',
            'galon' => '💧',
            'laundry' => '👕',
            default => '💳',
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
            $serverKey = config('midtrans.server_key');
            $isProduction = config('midtrans.is_production');
            $skipSsl = config('midtrans.skip_ssl_verification');

            \Log::info('Midtrans Config Check', [
                'server_key' => $serverKey,
                'is_production' => $isProduction,
            ]);

            $baseUrl = $isProduction
                ? 'https://app.midtrans.com/snap/v1/transactions'
                : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

            // Use orderan_id as order_id for Midtrans
            $orderId = $this->orderan_id ?? $this->order_id ?? 'PAY-' . $this->id_pembayaran . '-' . time();

            // Get customer details based on payment type
            $customerFirstName = 'Customer';
            $customerEmail = 'customer@example.com';
            $customerPhone = '-';

            if ($this->tipe_pembayaran === 'kamar' && $this->pesan) {
                $customerFirstName = $this->pesan->penyewa->nama_lengkap;
                $customerEmail = $this->pesan->penyewa->email;
                $customerPhone = $this->pesan->penyewa->no_hp;
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $orderId,
                    'gross_amount' => (int) $this->jumlah_bayar,
                ],
                'customer_details' => [
                    'first_name' => $customerFirstName,
                    'email' => $customerEmail,
                    'phone' => $customerPhone,
                ],
            ];

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

            if ($curlError) {
                throw new \Exception('CURL Error: ' . $curlError);
            }

            if ($httpCode !== 200 && $httpCode !== 201) {
                throw new \Exception('Midtrans API Error: HTTP ' . $httpCode);
            }

            $result = json_decode($response);

            if (!$result || !isset($result->token)) {
                throw new \Exception('Invalid response from Midtrans');
            }

            $this->order_id = $orderId;
            $this->snap_token = $result->token;
            $this->save();

            return $this->snap_token;
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
            \Log::info('Midtrans Callback Processing', [
                'order_id' => $notificationData['order_id'] ?? 'N/A',
                'transaction_status' => $notificationData['transaction_status'] ?? 'N/A',
            ]);

            $statusCode = $notificationData['status_code'] ?? '';
            $transactionId = $notificationData['transaction_id'] ?? '';
            $orderId = $notificationData['order_id'] ?? '';
            $grossAmount = $notificationData['gross_amount'] ?? '';
            $transactionStatus = $notificationData['transaction_status'] ?? '';
            $signatureKey = $notificationData['signature_key'] ?? '';

            $computedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

            if ($signatureKey !== $computedSignature) {
                \Log::warning('Invalid Midtrans signature key');
            }

            $this->transaction_id = $transactionId;
            $this->transaction_status = $transactionStatus;
            $this->payment_type = $notificationData['payment_type'] ?? null;
            
            // Map Midtrans payment_type to jenis_pembayaran
            $midtransPaymentType = $notificationData['payment_type'] ?? null;
            if ($midtransPaymentType) {
                $this->jenis_pembayaran = $this->mapPaymentType($midtransPaymentType);
            }

            if (isset($notificationData['transaction_time'])) {
                $this->transaction_time = $notificationData['transaction_time'];
            }

            $this->save();

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
        // Handle based on payment type
        return match($this->tipe_pembayaran) {
            'makanan' => $this->processFoodOrderStatus($status),
            'galon' => $this->processGalonOrderStatus($status),
            'laundry' => $this->processLaundryOrderStatus($status),
            default => $this->processKamarPaymentStatus($status),
        };
    }

    /**
     * Map Midtrans payment_type to jenis_pembayaran.
     */
    private function mapPaymentType(string $paymentType): string
    {
        return match($paymentType) {
            'bank_transfer', 'va', 'bca_va', 'bni_va', 'permata_va', 'other_va', 'cimb_va' => 'transfer_bank',
            'gopay', 'gopay-wallet', 'shopeepay', 'dana', 'linkaja', 'ovo' => 'ewallet',
            'credit_card', 'kredit_card' => 'credit_card',
            'qris' => 'qris',
            'indomaret', 'alfamart' => 'over_counter',
            default => 'ewallet',
        };
    }

    /**
     * Process food order payment status update.
     */
    private function processFoodOrderStatus(string $status): bool
    {
        // Find food order by id_pesan (which stores id_pesanan_makanan for food payments)
        $order = \App\Models\PesananMakananHeader::find($this->id_pesan);

        if (!$order) {
            \Log::error('Food order not found for payment', [
                'pembayaran_id' => $this->id_pembayaran,
                'id_pesan' => $this->id_pesan,
            ]);
            return false;
        }

        \Log::info('Processing Food Order Payment', [
            'order_id' => $order->id_pesanan_makanan,
            'status' => $status,
        ]);

        return $this->updateOrderStatus($order, $status, 'food');
    }

    /**
     * Process galon order payment status update.
     */
    private function processGalonOrderStatus(string $status): bool
    {
        // Find galon order by id_pesan (which stores id_order_galon for galon payments)
        $order = \App\Models\PesananGalon::find($this->id_pesan);

        if (!$order) {
            \Log::error('Galon order not found for payment', [
                'pembayaran_id' => $this->id_pembayaran,
                'id_pesan' => $this->id_pesan,
            ]);
            return false;
        }

        \Log::info('Processing Galon Order Payment', [
            'order_id' => $order->id_order_galon,
            'status' => $status,
        ]);

        return $this->updateOrderStatus($order, $status, 'galon');
    }

    /**
     * Process laundry order payment status update.
     */
    private function processLaundryOrderStatus(string $status): bool
    {
        // Find laundry order by id_pesan (which stores id_order_laundry for laundry payments)
        $order = \App\Models\PesananLaundry::find($this->id_pesan);

        if (!$order) {
            \Log::error('Laundry order not found for payment', [
                'pembayaran_id' => $this->id_pembayaran,
                'id_pesan' => $this->id_pesan,
            ]);
            return false;
        }

        \Log::info('Processing Laundry Order Payment', [
            'order_id' => $order->id_order_laundry,
            'status' => $status,
        ]);

        switch ($status) {
            case 'settlement':
            case 'capture':
                // Payment successful - update status to sedang_dicuci
                if ($order->status_laundry === \App\Models\PesananLaundry::STATUS_MENUNGGU_BAYAR) {
                    $order->status_laundry = \App\Models\PesananLaundry::STATUS_SEDANG_DICUCI;
                    $order->save();
                }
                \Log::info('Laundry order payment settled', [
                    'order_id' => $order->id_order_laundry,
                    'new_status' => $order->status_laundry,
                ]);
                return true;

            case 'pending':
                \Log::info('Laundry order payment pending', ['order_id' => $order->id_order_laundry]);
                return true;

            case 'cancel':
            case 'deny':
            case 'expire':
                // Payment cancelled/expired
                if ($order->status_laundry === \App\Models\PesananLaundry::STATUS_MENUNGGU_BAYAR) {
                    $order->status_laundry = \App\Models\PesananLaundry::STATUS_DIBATALKAN;
                    $order->save();
                }
                \Log::info('Laundry order payment cancelled', [
                    'order_id' => $order->id_order_laundry,
                    'new_status' => $order->status_laundry,
                ]);
                return true;

            default:
                \Log::warning('Unknown laundry order payment status', [
                    'order_id' => $order->id_order_laundry,
                    'status' => $status,
                ]);
                return false;
        }
    }

    /**
     * Update order status helper method.
     */
    private function updateOrderStatus($order, string $status, string $type): bool
    {
        switch ($status) {
            case 'settlement':
            case 'capture':
                // Payment successful
                if ($type === 'food') {
                    if ($order->status_antar === \App\Models\PesananMakananHeader::STATUS_MENUNGGU_BAYAR) {
                        $order->status_antar = \App\Models\PesananMakananHeader::STATUS_DIPROSES;
                        $order->save();
                    }
                } else { // galon
                    if ($order->status_galon === \App\Models\PesananGalon::STATUS_MENUNGGU_BAYAR) {
                        $order->status_galon = \App\Models\PesananGalon::STATUS_DIPROSES;
                        $order->save();
                    }
                }
                \Log::info("{$type} order payment settled", [
                    'order_id' => $order->getKey(),
                    'new_status' => $type === 'food' ? $order->status_antar : $order->status_galon,
                ]);
                return true;

            case 'pending':
                \Log::info("{$type} order payment pending", ['order_id' => $order->getKey()]);
                return true;

            case 'cancel':
            case 'deny':
            case 'expire':
                // Payment cancelled/expired
                if ($type === 'food') {
                    if ($order->status_antar === \App\Models\PesananMakananHeader::STATUS_MENUNGGU_BAYAR) {
                        $order->status_antar = \App\Models\PesananMakananHeader::STATUS_DIBATALKAN;
                        $order->save();
                        // Restore stock
                        foreach ($order->details as $detail) {
                            $detail->makanan->increment('stok', $detail->jumlah);
                        }
                    }
                } else { // galon
                    if ($order->status_galon === \App\Models\PesananGalon::STATUS_MENUNGGU_BAYAR) {
                        $order->status_galon = \App\Models\PesananGalon::STATUS_DIBATALKAN;
                        $order->save();
                    }
                }
                \Log::info("{$type} order payment cancelled", [
                    'order_id' => $order->getKey(),
                    'new_status' => $type === 'food' ? $order->status_antar : $order->status_galon,
                ]);
                return true;

            default:
                \Log::warning("Unknown {$type} order payment status", [
                    'order_id' => $order->getKey(),
                    'status' => $status,
                ]);
                return false;
        }
    }

    /**
     * Process kamar payment status update.
     */
    private function processKamarPaymentStatus(string $status): bool
    {
        $pesan = $this->pesan;
        if (!$pesan) {
            return false;
        }

        switch ($status) {
            case 'settlement':
            case 'capture':
                if (in_array($pesan->status_pesan, [Pesan::STATUS_PROSES_VERIFIKASI, Pesan::STATUS_MENUNGGU_PEMBAYARAN])) {
                    $pesan->status_pesan = Pesan::STATUS_AKTIF;
                    $pesan->save();
                }
                if ($pesan->kamar && $pesan->kamar->status_kamar !== 'terisi') {
                    $pesan->kamar->status_kamar = 'terisi';
                    $pesan->kamar->save();
                }
                return true;

            case 'pending':
                return true;

            case 'cancel':
            case 'deny':
            case 'expire':
                $pesan->status_pesan = Pesan::STATUS_DIBATALKAN;
                $pesan->save();
                if ($pesan->kamar) {
                    $pesan->kamar->status_kamar = 'tersedia';
                    $pesan->kamar->save();
                }
                return true;

            default:
                return false;
        }
    }
}
