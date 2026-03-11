<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryKatalog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'laundry_katalog';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_laundry_tipe';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kost',
        'nama_layanan',
        'harga_per_kg',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_per_kg' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the kost that owns this laundry catalog.
     */
    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all orders for this laundry type.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(PesananLaundry::class, 'id_laundry_tipe', 'id_laundry_tipe');
    }

    /**
     * Get the formatted price per kg.
     */
    public function getFormattedHargaPerKgAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_per_kg, 0, ',', '.') . '/kg';
    }
}
