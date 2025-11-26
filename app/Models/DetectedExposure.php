<?php

namespace App\Models;

use App\Enums\Vendor;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Detected exposures table - normalized core detection data (IP+Port).
 *
 * Links to Attribution model via IP for network/location context.
 * Source tracking via relationships to vendor tables (bitsight_exposed_assets,
 * shodan_exposed_assets, censys_exposed_assets) using composite key (ip, port).
 *
 * @mixin IdeHelperDetectedExposure
 */
class DetectedExposure extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ip',
        'port',
        'source',
        'transport',
        'module',
        'first_detected_at',
        'last_detected_at',
    ];

    protected function casts(): array
    {
        return [
            'source' => Vendor::class,
            'first_detected_at' => 'datetime',
            'last_detected_at' => 'datetime',
        ];
    }

    /**
     * Get the attribution data for this detected exposure's IP.
     */
    public function attribution(): BelongsTo
    {
        return $this->belongsTo(Attribution::class, 'ip', 'ip');
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
     * Get list of vendors that have detected this exposure.
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
     * Scope query to find exposure by IP and Port.
     */
    public function scopeForIpPort(Builder $query, string $ip, int $port): Builder
    {
        return $query->where('ip', $ip)->where('port', $port);
    }
}
