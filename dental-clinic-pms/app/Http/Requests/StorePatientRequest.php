<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'emergency_contact_name' => 'required|string|max:255',
            'emergency_contact_phone' => 'required|string|max:20',
            'emergency_contact_relationship' => 'required|string|max:100',
            'allergies' => 'nullable|string',
            'medical_conditions' => 'nullable|string',
            'current_medications' => 'nullable|string',
            'medical_notes' => 'nullable|string',
            'dental_history' => 'nullable|string',
            'previous_treatments' => 'nullable|string',
            'dental_notes' => 'nullable|string',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_policy_number' => 'nullable|string|max:100',
            'insurance_group_number' => 'nullable|string|max:100',
            'insurance_expiry_date' => 'nullable|date|after:today',
        ];
    }
}