<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePatientRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|min:2|max:50',
            'last_name' => 'required|string|min:2|max:50',
            'date_of_birth' => 'required|date|before:today',
            'gender' => ['required', Rule::in(['male', 'female', 'other', 'prefer_not_to_say'])],
            'phone' => 'required|string|regex:/^[\+]?[1-9][\d]{0,15}$/',
            'email' => 'nullable|email|max:255',
            'address' => 'required|string|min:5|max:200',
            'city' => 'required|string|min:2|max:50',
            'state' => 'nullable|string|min:2|max:50',
            'zip_code' => 'required|string|min:3|max:10',
            'emergency_contact' => 'required|string|min:2|max:100',
            'emergency_phone' => 'required|string|regex:/^[\+]?[1-9][\d]{0,15}$/',
            'medical_history' => 'nullable|string|max:2000',
            'dental_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'medications' => 'nullable|string|max:1000',
            'insurance_provider' => 'nullable|string|max:100',
            'insurance_number' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'date_of_birth.required' => 'Date of birth is required',
            'date_of_birth.before' => 'Date of birth must be in the past',
            'gender.required' => 'Gender is required',
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Please enter a valid phone number',
            'email.email' => 'Please enter a valid email address',
            'address.required' => 'Address is required',
            'city.required' => 'City is required',
            'zip_code.required' => 'ZIP code is required',
            'emergency_contact.required' => 'Emergency contact is required',
            'emergency_phone.required' => 'Emergency phone is required',
            'emergency_phone.regex' => 'Please enter a valid emergency phone number',
        ];
    }
}