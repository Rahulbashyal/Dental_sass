<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->clinic_id;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'last_name' => 'required|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'email' => [
                'nullable',
                'email:rfc,dns',
                'max:255',
                Rule::unique('patients')->where(function ($query) {
                    return $query->where('clinic_id', Auth::user()->clinic_id);
                }),
            ],
            'phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/',
            'date_of_birth' => 'nullable|date|before:today|after:1900-01-01',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'state' => 'nullable|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'postal_code' => 'nullable|string|max:20|regex:/^[0-9\-\s]+$/',
            'emergency_contact_name' => 'nullable|string|max:255|regex:/^[a-zA-Z\s]+$/',
            'emergency_contact_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\-\(\)\s]+$/',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'insurance_provider' => 'nullable|string|max:255',
            'insurance_number' => 'nullable|string|max:100|regex:/^[a-zA-Z0-9\-\s]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.regex' => 'First name can only contain letters and spaces.',
            'last_name.regex' => 'Last name can only contain letters and spaces.',
            'email.unique' => 'A patient with this email already exists in your clinic.',
            'phone.regex' => 'Please provide a valid phone number.',
            'date_of_birth.before' => 'Date of birth must be in the past.',
            'date_of_birth.after' => 'Please provide a valid date of birth.',
            'city.regex' => 'City name can only contain letters and spaces.',
            'state.regex' => 'State name can only contain letters and spaces.',
            'postal_code.regex' => 'Postal code can only contain numbers, hyphens, and spaces.',
            'emergency_contact_name.regex' => 'Emergency contact name can only contain letters and spaces.',
            'emergency_contact_phone.regex' => 'Please provide a valid emergency contact phone number.',
            'insurance_number.regex' => 'Insurance number can only contain letters, numbers, hyphens, and spaces.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'first_name' => $this->first_name ? ucwords(strtolower(trim($this->first_name))) : null,
            'last_name' => $this->last_name ? ucwords(strtolower(trim($this->last_name))) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'phone' => $this->phone ? preg_replace('/[^\d\+\-\(\)\s]/', '', $this->phone) : null,
            'city' => $this->city ? ucwords(strtolower(trim($this->city))) : null,
            'state' => $this->state ? ucwords(strtolower(trim($this->state))) : null,
            'emergency_contact_name' => $this->emergency_contact_name ? ucwords(strtolower(trim($this->emergency_contact_name))) : null,
            'emergency_contact_phone' => $this->emergency_contact_phone ? preg_replace('/[^\d\+\-\(\)\s]/', '', $this->emergency_contact_phone) : null,
            'medical_history' => $this->medical_history ? strip_tags($this->medical_history) : null,
            'allergies' => $this->allergies ? strip_tags($this->allergies) : null,
            'insurance_provider' => $this->insurance_provider ? trim($this->insurance_provider) : null,
            'insurance_number' => $this->insurance_number ? strtoupper(trim($this->insurance_number)) : null,
        ]);
    }
}