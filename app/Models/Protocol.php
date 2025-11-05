<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProtocol
 */
class Protocol extends Model
{
    protected $fillable = [
        'module',
        'protocol',
        'severity',
        'description',
        'modifier',
    ];
}
