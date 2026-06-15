<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criteria extends Model
{
    protected $table = 'criterias';

    protected $fillable = [
        'user_id',
        'criteria_name',
        'type',
        'weight',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplierValues(): HasMany
    {
        return $this->hasMany(Supplier_Value::class, 'id_criteria');
    }
}

