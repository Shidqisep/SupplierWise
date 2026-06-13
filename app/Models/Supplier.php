<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * =====================================================
 * MODEL: SUPPLIER
 * =====================================================
 *
 * Implementasi Kriptografi:
 *
 * Field 'contact' dan 'address' dienkripsi menggunakan
 * Laravel Encryption (AES-256-CBC) melalui cast 'encrypted'.
 *
 * - Data otomatis dienkripsi saat disimpan ke database
 * - Data otomatis didekripsi saat dibaca dari database
 * - Menggunakan APP_KEY sebagai encryption key
 * - Cipher: AES-256-CBC (dikonfigurasi di config/app.php)
 *
 * Ini memastikan data sensitif supplier (kontak, alamat)
 * tidak tersimpan dalam bentuk plaintext di database.
 */
class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $fillable = [
        'user_id',
        'supplier_name',
        'contact',
        'address',
        'category_id',
    ];

    /**
     * Cast fields — contact dan address dienkripsi otomatis.
     *
     * 'encrypted' cast menggunakan Laravel Crypt facade
     * dengan algoritma AES-256-CBC untuk enkripsi/dekripsi.
     */
    protected $casts = [
        'contact' => 'encrypted',
        'address' => 'encrypted',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function supplierValues(): HasMany
    {
        return $this->hasMany(Supplier_Value::class, 'id_supplier');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
