<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KostSetting extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kost_settings';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id_setting';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_kost',
        'enable_makanan',
        'enable_galon',
        'enable_laundry',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enable_makanan' => 'boolean',
        'enable_galon' => 'boolean',
        'enable_laundry' => 'boolean',
    ];

    /**
     * Get the kost that owns this setting.
     */
    public function kost(): BelongsTo
    {
        return $this->belongsTo(Kost::class, 'id_kost', 'id_kost');
    }

    /**
     * Check if a feature is enabled.
     */
    public function isFeatureEnabled(string $feature): bool
    {
        return match($feature) {
            'makanan' => $this->enable_makanan,
            'galon' => $this->enable_galon,
            'laundry' => $this->enable_laundry,
            default => false,
        };
    }

    /**
     * Get or create setting for a kost.
     */
    public static function getOrCreate(int $kostId): self
    {
        return static::firstOrCreate(
            ['id_kost' => $kostId],
            [
                'enable_makanan' => false,
                'enable_galon' => false,
                'enable_laundry' => false,
            ]
        );
    }
}
