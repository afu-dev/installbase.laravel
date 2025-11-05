<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperAttribution
 */
class Attribution extends Model
{
    protected $fillable = [
        'ip',
        'entity',
        'sector',
        'domain',
        'hostnames',
        'isp',
        'asn',
        'whois',
        'city',
        'country_code',
        'source_of_attribution',
        'last_exposure_at',
    ];

    protected function casts(): array
    {
        return [
            'last_exposure_at' => 'datetime',
        ];
    }

    /**
     * Get all detected exposures for this IP.
     */
    public function detectedExposures(): HasMany
    {
        return $this->hasMany(DetectedExposure::class, 'ip', 'ip');
    }

    /**
     * Scope query to find attribution by IP.
     */
    public function scopeForIp(Builder $query, string $ip): Builder
    {
        return $query->where('ip', $ip);
    }
}
