<?php

namespace App\Models;

use App\Enums\Vendor;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperQuery
 */
class Query extends Model
{
    protected $fillable = [
        'product',
        'protocol',
        'query',
        'query_type',
        'vendor',
    ];

    protected function casts(): array
    {
        return [
            'vendor' => Vendor::class,
        ];
    }
}
