<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code', 'item_name', 'description', 'brand', 'manufacturer',
        'category', 'subcategory', 'supplier_id', 'supplier_item_code',
        'unit_of_measure', 'quantity_in_stock', 'reorder_level',
        'maximum_stock_level', 'minimum_stock_level', 'unit_cost',
        'selling_price', 'last_purchase_date', 'last_purchase_cost',
        'has_expiry', 'expiry_date', 'batch_number', 'lot_number',
        'storage_location', 'storage_requirements', 'is_active',
        'low_stock_alert_sent', 'low_stock_alert_sent_at',
        'expiry_alert_sent', 'expiry_alert_sent_at', 'notes', 'specifications',
    ];

    protected $casts = [
        'unit_cost' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'last_purchase_cost' => 'decimal:2',
        'last_purchase_date' => 'date',
        'expiry_date' => 'date',
        'has_expiry' => 'boolean',
        'is_active' => 'boolean',
        'low_stock_alert_sent' => 'boolean',
        'expiry_alert_sent' => 'boolean',
        'low_stock_alert_sent_at' => 'datetime',
        'expiry_alert_sent_at' => 'datetime',
        'specifications' => 'array',
    ];

    public function supplier(): BelongsTo { return $this->belongsTo(Supplier::class); }
    
    public function isLowStock(): bool { return $this->quantity_in_stock <= $this->reorder_level; }
    public function isExpired(): bool { return $this->has_expiry && $this->expiry_date && $this->expiry_date->isPast(); }
}
