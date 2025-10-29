<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperImportError
 */
class ImportError extends Model
{
    protected $fillable = [
        'vendor',
        'source_file',
        'row_number',
        'ip',
        'port',
        'error_message',
    ];
}
