<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $fillable = [
        'category_name',
    ];

    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'category_id');
    }
}
