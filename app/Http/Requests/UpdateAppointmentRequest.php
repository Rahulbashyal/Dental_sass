<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UpdateAppointmentRequest extends FormRequest
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
            'appointment_date' => 'required|date',
            'appointment_time' => 'required|date_format:H:i',
            'type' => 'required|string|max:100|in:consultation,cleaning,filling,extraction,checkup,emergency,surgery,orthodontics',
            'status' => 'required|string|in:scheduled,confirmed,completed,cancelled,no-show',
            'treatment_cost' => 'nullable|numeric|min:0|max:999999.99',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.exists' => 'The selected patient does not belong to your clinic.',
            'status.in' => 'Please select a valid appointment status.',
            'type.in' => 'Please select a valid appointment type.',
            'treatment_cost.max' => 'Treatment cost cannot exceed NPR 999,999.99',
            'notes.max' => 'Notes cannot exceed 1000 characters.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'notes' => $this->notes ? strip_tags($this->notes) : null,
            'type' => $this->type ? strtolower(trim($this->type)) : null,
        ]);
    }
}
