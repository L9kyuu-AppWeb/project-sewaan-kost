<?php

namespace App;

/**
 * Payment Helper Utility
 * 
 * Helper class for generating payment labels and order IDs.
 * 
 * Payment Type Labels:
 * - KAMAR-{id}-{timestamp}M  (e.g., KAMAR-1-1773182971M)
 * - MAKANAN-{id}-{timestamp} (e.g., MAKANAN-1-1773182972)
 * - GALON-{id}-{timestamp}   (e.g., GALON-1-1773182973)
 * - LAUNDRY-{id}-{timestamp} (e.g., LAUNDRY-1-1773182971)
 */
class PaymentHelper
{
    /**
     * Payment type constants
     */
    const TYPE_KAMAR = 'kamar';
    const TYPE_MAKANAN = 'makanan';
    const TYPE_GALON = 'galon';
    const TYPE_LAUNDRY = 'laundry';

    /**
     * Generate order ID based on payment type.
     *
     * @param string $type Payment type (kamar, makanan, galon, laundry)
     * @param int $referenceId Reference ID (id_pesan or order ID)
     * @param int|null $timestamp Optional timestamp (defaults to current time)
     * @return string Formatted order ID
     */
    public static function generateOrderId(string $type, int $referenceId, ?int $timestamp = null): string
    {
        $timestamp = $timestamp ?? time();
        $typeUpper = strtoupper($type);
        $suffix = '';

        // Add suffix for specific types
        if ($typeUpper === 'KAMAR') {
            $suffix = 'M';
        }

        return "{$typeUpper}-{$referenceId}-{$timestamp}{$suffix}";
    }

    /**
     * Generate order ID for kamar payment.
     */
    public static function generateKamarOrderId(int $idPesan, ?int $timestamp = null): string
    {
        return self::generateOrderId(self::TYPE_KAMAR, $idPesan, $timestamp);
    }

    /**
     * Generate order ID for makanan payment.
     */
    public static function generateMakananOrderId(int $idOrderMakan, ?int $timestamp = null): string
    {
        return self::generateOrderId(self::TYPE_MAKANAN, $idOrderMakan, $timestamp);
    }

    /**
     * Generate order ID for galon payment.
     */
    public static function generateGalonOrderId(int $referenceId, ?int $timestamp = null): string
    {
        return self::generateOrderId(self::TYPE_GALON, $referenceId, $timestamp);
    }

    /**
     * Generate order ID for laundry payment.
     */
    public static function generateLaundryOrderId(int $referenceId, ?int $timestamp = null): string
    {
        return self::generateOrderId(self::TYPE_LAUNDRY, $referenceId, $timestamp);
    }

    /**
     * Get payment type label.
     */
    public static function getTypeLabel(string $type): string
    {
        return match($type) {
            self::TYPE_KAMAR => 'Kamar',
            self::TYPE_MAKANAN => 'Makanan',
            self::TYPE_GALON => 'Galon',
            self::TYPE_LAUNDRY => 'Laundry',
            default => 'Tidak Diketahui',
        };
    }

    /**
     * Get payment type icon.
     */
    public static function getTypeIcon(string $type): string
    {
        return match($type) {
            self::TYPE_KAMAR => '🏠',
            self::TYPE_MAKANAN => '🍽️',
            self::TYPE_GALON => '💧',
            self::TYPE_LAUNDRY => '👕',
            default => '💳',
        };
    }

    /**
     * Parse order ID to extract type and reference ID.
     *
     * @param string $orderId Order ID to parse (e.g., KAMAR-1-1773182971M)
     * @return array|null Array with 'type', 'reference_id', 'timestamp', or null if invalid
     */
    public static function parseOrderId(string $orderId): ?array
    {
        // Pattern: TYPE-ID-TIMESTAMP[SUFFIX]
        $pattern = '/^(KAMAR|MAKANAN|GALON|LAUNDRY)-(\d+)-(\d+)(M)?$/';
        
        if (preg_match($pattern, $orderId, $matches)) {
            return [
                'type' => strtolower($matches[1]),
                'reference_id' => (int) $matches[2],
                'timestamp' => (int) $matches[3],
                'is_kamar' => $matches[1] === 'KAMAR',
            ];
        }

        return null;
    }

    /**
     * Validate order ID format.
     */
    public static function isValidOrderId(string $orderId): bool
    {
        return self::parseOrderId($orderId) !== null;
    }

    /**
     * Get full payment label with icon.
     */
    public static function getPaymentLabel(string $type): string
    {
        $icon = self::getTypeIcon($type);
        $label = self::getTypeLabel($type);
        return "{$icon} {$label}";
    }
}
