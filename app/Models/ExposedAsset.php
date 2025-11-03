<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Unified exposed assets table - silver/gold layer aggregating data from all vendors.
 * 
 * Source tracking via relationships to vendor tables (bitsight_exposed_assets,
 * shodan_exposed_assets, censys_exposed_assets) using composite key (ip, port).
 * 
 * Merge priority: Bitsight > Shodan > Censys for single-value fields.
 * Hostnames are deduplicated and combined from all vendors.
 *
 * @mixin IdeHelperExposedAsset
 */
class ExposedAsset extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ip',
        'port',
        'module',
        'transport',
        'first_detected_at',
        'last_detected_at',
        'hostnames',
        'entity',
        'isp',
        'country_code',
        'city',
        'os',
        'asn',
        'product',
        'product_sn',
        'version',
        'raw_data',
    ];

    protected function casts(): array
    {
        return [
            'first_detected_at' => 'datetime',
            'last_detected_at' => 'datetime',
        ];
    }

    /**
     * Get all Bitsight detections for this IP:Port combination.
     */
    public function bitsightDetections(): HasMany
    {
        return $this->hasMany(BitsightExposedAsset::class, 'ip', 'ip')
            ->where('bitsight_exposed_assets.port', '=', $this->port);
    }

    /**
     * Get all Shodan detections for this IP:Port combination.
     */
    public function shodanDetections(): HasMany
    {
        return $this->hasMany(ShodanExposedAsset::class, 'ip', 'ip')
            ->where('shodan_exposed_assets.port', '=', $this->port);
    }

    /**
     * Get all Censys detections for this IP:Port combination.
     */
    public function censysDetections(): HasMany
    {
        return $this->hasMany(CensysExposedAsset::class, 'ip', 'ip')
            ->where('censys_exposed_assets.port', '=', $this->port);
    }

    /**
     * Get list of vendors that have detected this asset.
     *
     * @return array<string> Array of vendor names (e.g., ['bitsight', 'shodan'])
     */
    public function detectedByVendors(): array
    {
        $vendors = [];

        if ($this->bitsightDetections()->exists()) {
            $vendors[] = 'bitsight';
        }

        if ($this->shodanDetections()->exists()) {
            $vendors[] = 'shodan';
        }

        if ($this->censysDetections()->exists()) {
            $vendors[] = 'censys';
        }

        return $vendors;
    }

    /**
     * Scope query to find asset by IP and Port.
     */
    public function scopeForIpPort(Builder $query, string $ip, int $port): Builder
    {
        return $query->where('ip', $ip)->where('port', $port);
    }
}
