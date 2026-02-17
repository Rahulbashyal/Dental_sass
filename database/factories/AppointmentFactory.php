<?php

namespace Database\Factories;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition()
    {
        return [
            'clinic_id' => null,
            'patient_id' => null,
            'dentist_id' => null,
            'appointment_date' => Carbon::today()->toDateString(),
            'appointment_time' => Carbon::now()->toDateTimeString(),
            'duration' => 30,
            'status' => 'scheduled',
        ];
    }
}
