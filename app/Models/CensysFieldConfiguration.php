<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCensysFieldConfiguration
 */
class CensysFieldConfiguration extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'protocol',
        'fields',
    ];
}
