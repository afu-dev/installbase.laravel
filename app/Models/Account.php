<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperAccount
 */
class Account extends Model
{
    protected $primaryKey = 'entity';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'entity',
        'sector',
        'entity_country',
        'url',
        'point_of_contact',
        'type_of_account',
        'account_manager',
    ];
}
