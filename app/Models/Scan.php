<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperScan
 */
class Scan extends Model
{
    public function executions(): HasMany
    {
        return $this->hasMany(Execution::class);
    }
}
