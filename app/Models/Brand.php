<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBrand
 */
class Brand extends Model
{
    protected $primaryKey = 'brand';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'brand',
    ];
}
