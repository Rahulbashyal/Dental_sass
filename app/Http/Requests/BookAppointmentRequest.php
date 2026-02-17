<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BookAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public booking
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email:rfc,dns|max:255',
            'phone' => 'required|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/',
            'appointment_date' => 'required|date|after:today|before:' . now()->addMonths(3)->format('Y-m-d'),
            'appointment_time' => 'required|date_format:H:i',
            'dentist_id' => [
                'required',
                'integer',
                'min:1',
                Rule::exists('users', 'id')->where(function ($query) {
                    $clinic = request()->route('clinic');
                    if ($clinic && is_object($clinic) && property_exists($clinic, 'id')) {
                        return $query->where('clinic_id', $clinic->id);
                    }
                    return $query->where('id', 0); // No match if clinic not found
                }),
            ],
            'type' => 'required|string|max:100|in:consultation,cleaning,filling,extraction,checkup,emergency',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'email.email' => 'Please provide a valid email address.',
            'phone.regex' => 'Please provide a valid phone number.',
            'appointment_date.after' => 'Appointment must be scheduled for tomorrow or later.',
            'appointment_date.before' => 'Appointments can only be booked up to 3 months in advance.',
            'dentist_id.exists' => 'The selected dentist is not available at this clinic.',
            'type.in' => 'Please select a valid appointment type.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->first_name ? ucwords(strtolower(trim($this->first_name))) : null,
            'last_name' => $this->last_name ? ucwords(strtolower(trim($this->last_name))) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'phone' => $this->phone ? preg_replace('/[^\d\+\-\(\)\s]/', '', $this->phone) : null,
            'type' => $this->type ? strtolower(trim($this->type)) : null,
        ]);
    }
}