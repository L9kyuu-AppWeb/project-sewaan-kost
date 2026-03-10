<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Pesan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans HTTP notification callback.
     */
    public function callback(Request $request): JsonResponse
    {
        try {
            $notificationData = $request->all();

            \Log::info('Midtrans callback received', ['data' => $notificationData]);

            // Find payment by order_id
            $order_id = $notificationData['order_id'] ?? null;
            if (!$order_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order ID not found',
                ], 400);
            }

            // Extract payment ID from order_id (format: PESAN-{id}-{timestamp})
            $parts = explode('-', $order_id);
            if (count($parts) < 3) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid order ID format',
                ], 400);
            }

            $pesanId = (int) $parts[1];

            $pembayaran = Pembayaran::where('order_id', $order_id)->first();

            if (!$pembayaran) {
                // Try to find by pesan_id if order_id doesn't match
                $pembayaran = Pembayaran::whereHas('pesan', function ($q) use ($pesanId) {
                    $q->where('id_pesan', $pesanId);
                })->latest('id_pembayaran')->first();

                if (!$pembayaran) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment record not found',
                    ], 404);
                }
            }

            // Handle the callback
            $success = $pembayaran->handleCallback($notificationData);

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Payment status updated successfully',
                    'data' => [
                        'order_id' => $pembayaran->order_id,
                        'transaction_id' => $pembayaran->transaction_id,
                        'transaction_status' => $pembayaran->transaction_status,
                    ],
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to process payment status',
                ], 500);
            }
        } catch (\Exception $e) {
            \Log::error('Midtrans callback error: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Internal server error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show payment page with Midtrans Snap.
     */
    public function pay(Pesan $pesan)
    {
        // Verify ownership
        if ($pesan->id_penyewa !== auth()->id()) {
            abort(403, 'Unauthorized access.');
        }

        // Can only pay if status is menunggu_pembayaran
        if ($pesan->status_pesan !== Pesan::STATUS_MENUNGGU_PEMBAYARAN) {
            return redirect()->route('pesan.show', $pesan->id_pesan)
                ->with('error', 'Pembayaran sudah diproses atau tidak tersedia.');
        }

        // Get or create payment record
        $pembayaran = $pesan->payments()->whereNull('transaction_id')->latest('id_pembayaran')->first();

        if (!$pembayaran) {
            // Create new payment record
            $pembayaran = $pesan->payments()->create([
                'order_id' => 'PESAN-' . $pesan->id_pesan . '-' . time(),
                'jenis_pembayaran' => 'transfer_bank',
                'jumlah_bayar' => $pesan->total_harga,
                'tanggal_bayar' => now(),
                'status_verifikasi' => 'pending',
                'transaction_status' => 'pending',
            ]);
        }

        // Generate Snap Token
        $snapToken = $pembayaran->generateSnapToken();

        if (!$snapToken) {
            return redirect()->route('pesan.show', $pesan->id_pesan)
                ->with('error', 'Gagal menghasilkan token pembayaran. Silakan hubungi admin.');
        }

        return view('pesan.midtrans-pay', compact('pesan', 'pembayaran', 'snapToken'));
    }

    /**
     * Payment success page.
     */
    public function success(Pesan $pesan)
    {
        return view('pesan.midtrans-success', compact('pesan'));
    }

    /**
     * Payment failed page.
     */
    public function failed(Pesan $pesan)
    {
        return view('pesan.midtrans-failed', compact('pesan'));
    }
}
