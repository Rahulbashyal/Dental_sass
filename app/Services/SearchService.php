<?php

namespace App\Services;

use App\Models\{Patient, Appointment};
use Illuminate\Support\Facades\DB;

class SearchService
{
    public function advancedSearch($query, $filters = []): array
    {
        $results = [];
        
        if (empty($filters) || in_array('patients', $filters)) {
            $results['patients'] = $this->searchPatients($query);
        }
        
        if (empty($filters) || in_array('appointments', $filters)) {
            $results['appointments'] = $this->searchAppointments($query);
        }
        
        return $results;
    }

    private function searchPatients($query): array
    {
        return Patient::where(function($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
              ->orWhere('last_name', 'LIKE', "%{$query}%")
              ->orWhere('email', 'LIKE', "%{$query}%")
              ->orWhere('phone', 'LIKE', "%{$query}%")
              ->orWhere('patient_id', 'LIKE', "%{$query}%");
        })
        ->limit(20)
        ->get()
        ->toArray();
    }

    private function searchAppointments($query): array
    {
        return Appointment::with('patient')
            ->whereHas('patient', function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('last_name', 'LIKE', "%{$query}%");
            })
            ->orWhere('notes', 'LIKE', "%{$query}%")
            ->limit(20)
            ->get()
            ->toArray();
    }

    public function createSearchIndex()
    {
        // Create full-text search indexes
        DB::statement('ALTER TABLE patients ADD FULLTEXT(first_name, last_name, email)');
        DB::statement('ALTER TABLE appointments ADD FULLTEXT(notes, treatment_type)');
    }

    public function fullTextSearch($query): array
    {
        $patients = DB::select("
            SELECT *, MATCH(first_name, last_name, email) AGAINST(? IN NATURAL LANGUAGE MODE) as relevance
            FROM patients 
            WHERE MATCH(first_name, last_name, email) AGAINST(? IN NATURAL LANGUAGE MODE)
            ORDER BY relevance DESC
            LIMIT 20
        ", [$query, $query]);

        return ['patients' => $patients];
    }
}