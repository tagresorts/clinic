<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentRequest extends FormRequest
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
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'duration' => 'required|integer|min:15|max:480', // 15 minutes to 8 hours
            'type' => 'required|in:consultation,cleaning,extraction,filling,root_canal,crown,whitening,emergency,other',
            'reason' => 'required|string',
            'notes' => 'nullable|string',
            'status' => 'required|in:scheduled,confirmed,completed,cancelled,no_show',
        ];

        // For new appointments, ensure date is in the future
        if ($this->isMethod('POST')) {
            $rules['appointment_date'] = 'required|date|after:now';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'dentist_id.required' => 'Dentist is required.',
            'dentist_id.exists' => 'Selected dentist does not exist.',
            'appointment_date.required' => 'Appointment date is required.',
            'appointment_date.after' => 'Appointment date must be in the future.',
            'appointment_time.required' => 'Appointment time is required.',
            'appointment_time.date_format' => 'Please enter a valid time format (HH:MM).',
            'duration.required' => 'Duration is required.',
            'duration.min' => 'Duration must be at least 15 minutes.',
            'duration.max' => 'Duration cannot exceed 8 hours.',
            'type.required' => 'Appointment type is required.',
            'type.in' => 'Please select a valid appointment type.',
            'reason.required' => 'Reason for appointment is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Please select a valid status.',
        ];
    }
}