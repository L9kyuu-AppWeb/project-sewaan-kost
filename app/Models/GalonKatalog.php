<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GalonKatalog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galon_katalog';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_galon_tipe';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kost',
        'nama_air',
        'harga',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the kost that owns this galon catalog.
     */
    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    /**
     * Get all orders for this galon type.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(PesananGalon::class, 'id_galon_tipe', 'id_galon_tipe');
    }

    /**
     * Get the formatted price.
     */
    public function getFormattedHargaAttribute(): string
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }
}
