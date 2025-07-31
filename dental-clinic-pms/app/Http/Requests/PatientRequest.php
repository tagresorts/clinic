<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
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

        // Add is_active field for updates
        if ($this->isMethod('PUT') || $this->isMethod('PATCH')) {
            $rules['is_active'] = 'boolean';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required.',
            'last_name.required' => 'Last name is required.',
            'date_of_birth.required' => 'Date of birth is required.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'gender.required' => 'Gender is required.',
            'gender.in' => 'Gender must be male, female, or other.',
            'address.required' => 'Address is required.',
            'phone.required' => 'Phone number is required.',
            'email.email' => 'Please enter a valid email address.',
            'emergency_contact_name.required' => 'Emergency contact name is required.',
            'emergency_contact_phone.required' => 'Emergency contact phone is required.',
            'emergency_contact_relationship.required' => 'Emergency contact relationship is required.',
            'insurance_expiry_date.after' => 'Insurance expiry date must be in the future.',
        ];
    }
}