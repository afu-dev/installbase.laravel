<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCountry
 */
class Country extends Model
{
    protected $primaryKey = 'country_code2';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'country_code2',
        'country_code3',
        'country',
        'region',
        'ciso_region',
        'ciso_zone',
        'operation_zone',
    ];
}
