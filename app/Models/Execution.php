<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperExecution
 */
class Execution extends Model
{
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }

    public function scan(): BelongsTo
    {
        return $this->belongsTo(Scan::class);
    }

    public function vendorQuery(): BelongsTo
    {
        return $this->belongsTo(Query::class, 'query_id');
    }
}
