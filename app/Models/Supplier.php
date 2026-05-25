<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'supplier_name',
        'contact',
        'address',
    ];

    public function supplierValues(): HasMany
    {
        return $this->hasMany(Supplier_Value::class, 'id_supplier');
    }
}
