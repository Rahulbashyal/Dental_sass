<?php

namespace Tests\Feature;

use App\Models\{User, Clinic, Patient, Appointment};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AppointmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_appointments_via_api()
    {
        $clinic = Clinic::factory()->create();
        $user = User::factory()->create(['clinic_id' => $clinic->id]);
        $patient = Patient::factory()->create(['clinic_id' => $clinic->id]);
        Appointment::factory()->create([
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'dentist_id' => $user->id
        ]);

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/appointments');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [['id', 'patient', 'dentist', 'appointment_date']]
                ]);
    }

    public function test_api_requires_authentication()
    {
        $response = $this->getJson('/api/appointments');
        $response->assertStatus(401);
    }
}
