<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_name', 'company_registration_number', 'address', 'city',
        'state_province', 'postal_code', 'country', 'contact_person_name',
        'contact_person_title', 'phone', 'mobile', 'email', 'website',
        'products_services', 'payment_terms', 'credit_limit', 'notes',
        'is_active', 'preferred_supplier',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function inventoryItems(): HasMany { return $this->hasMany(InventoryItem::class); }
}
