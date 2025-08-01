<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TreatmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'appointment_id', 'dentist_id', 'treatment_plan_id',
        'treatment_date', 'treatment_notes',
        'post_treatment_instructions', 'observations', 'teeth_treated',
        'dental_chart_updates', 'medications_prescribed', 'follow_up_required',
        'next_visit_recommended', 'attached_images', 'attached_documents',
        'treatment_outcome', 'complications_notes', 'treatment_cost', 'billed',
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'next_visit_recommended' => 'date',
        // 'procedures_performed' => 'array', // Removed as it will be handled by many-to-many
        'teeth_treated' => 'array',
        'dental_chart_updates' => 'array',
        'attached_images' => 'array',
        'attached_documents' => 'array',
        'treatment_cost' => 'decimal:2',
        'billed' => 'boolean',
    ];

    // ... (other methods)

    /**
     * Procedures performed in this record
     */
    public function procedures(): BelongsToMany
    {
        return $this->belongsToMany(Procedure::class, 'treatment_record_procedure');
    }

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function appointment(): BelongsTo { return $this->belongsTo(Appointment::class); }
    public function dentist(): BelongsTo { return $this->belongsTo(User::class, 'dentist_id'); }
    public function treatmentPlan(): BelongsTo { return $this->belongsTo(TreatmentPlan::class); }
}
