<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DentalChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'updated_by', 'tooth_number', 'tooth_type', 'tooth_surface',
        'current_condition', 'condition_details', 'filling_material',
        'restoration_notes', 'treatment_date', 'mobility', 'probing_depth_mesial',
        'probing_depth_distal', 'probing_depth_buccal', 'probing_depth_lingual',
        'bleeding_on_probing', 'plaque_present', 'calculus_present',
        'tooth_color', 'appearance_notes', 'treatment_record_id',
        'attached_images', 'requires_attention', 'priority', 'dentist_notes',
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'bleeding_on_probing' => 'boolean',
        'plaque_present' => 'boolean',
        'calculus_present' => 'boolean',
        'requires_attention' => 'boolean',
        'attached_images' => 'array',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function updatedBy(): BelongsTo { return $this->belongsTo(User::class, 'updated_by'); }
    public function treatmentRecord(): BelongsTo { return $this->belongsTo(TreatmentRecord::class); }
}
