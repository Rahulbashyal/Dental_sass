<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->clinic_id;
    }

    public function rules(): array
    {
        return [
            'patient_id' => [
                'required',
                'integer',
                Rule::exists('patients', 'id')->where(function ($query) {
                    return $query->where('clinic_id', Auth::user()->clinic_id);
                }),
            ],
            'dentist_id' => 'nullable|integer|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today|before:' . now()->addYear()->format('Y-m-d'),
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|string|max:100|in:consultation,cleaning,filling,extraction,checkup,emergency,surgery,orthodontics',
            'treatment_cost' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.exists' => 'The selected patient does not belong to your clinic.',
            'appointment_date.after_or_equal' => 'Appointment date cannot be in the past.',
            'appointment_date.before' => 'Appointment date cannot be more than 1 year in advance.',
            'appointment_time.date_format' => 'Please provide a valid time in HH:MM format.',
            'type.in' => 'Please select a valid appointment type.',
            'treatment_cost.max' => 'Treatment cost cannot exceed NPR 999,999.99',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        // Sanitize input
        $this->merge([
            'notes' => $this->notes ? strip_tags($this->notes) : null,
            'type' => $this->type ? strtolower(trim($this->type)) : null,
        ]);
    }
}