<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperBitsightExposedAsset
 */
class BitsightExposedAsset extends Model
{
    protected $fillable = [
        'execution_id',
        'ip',
        'port',
        'module',
        'detected_at',
        'raw_data',
        'hostnames',
        'entity',
        'isp',
        'country_code',
        'city',
        'os',
        'asn',
        'transport',
        'product',
        'product_sn',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'detected_at' => 'datetime',
        ];
    }

    public function execution(): BelongsTo
    {
        return $this->belongsTo(Execution::class);
    }
}
