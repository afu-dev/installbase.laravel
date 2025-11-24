<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperProtocol
 */
class Protocol extends Model
{
    protected $primaryKey = 'module';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'module',
        'protocol',
        'severity',
        'description',
        'modifier',
    ];
}
