<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWalkInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(['administrator', 'receptionist']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'appointment_datetime' => 'required|date',
            'dentist_id' => 'required|exists:users,id',
            'duration_minutes' => 'required|integer|min:15',
            'appointment_type' => 'required|string',
            'reason_for_visit' => 'nullable|string',
            'appointment_notes' => 'nullable|string',
            'procedure_ids' => 'required|array',
            'procedure_ids.*' => 'exists:procedures,id',
            'description' => 'nullable|string',
            'teeth' => 'nullable|string',
            'treatment_notes' => 'nullable|string',
        ];
    }
}