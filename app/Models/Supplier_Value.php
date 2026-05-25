<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supplier_Value extends Model
{
    protected $table = 'supplier__values';

    protected $fillable = [
        'id_supplier',
        'id_criteria',
        'score',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }

    public function criteria(): BelongsTo
    {
        return $this->belongsTo(Criteria::class, 'id_criteria');
    }
}
