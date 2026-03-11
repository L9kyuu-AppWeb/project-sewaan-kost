<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananMakananDetail extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pesanan_makanan_detail';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_detail';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_pesanan_makanan',
        'id_makanan',
        'jumlah',
        'harga_satuan',
        'subtotal',
        'catatan_item',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    /**
     * Get the header order that owns this detail.
     */
    public function header(): BelongsTo
    {
        return $this->belongsTo(PesananMakananHeader::class, 'id_pesanan_makanan', 'id_pesanan_makanan');
    }

    /**
     * Get the makanan item.
     */
    public function makanan(): BelongsTo
    {
        return $this->belongsTo(Makanan::class, 'id_makanan', 'id_makanan');
    }

    /**
     * Get the formatted subtotal.
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Get the formatted price per item.
     */
    public function getFormattedHargaSatuanAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan, 0, ',', '.');
    }
}
