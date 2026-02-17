<?php

namespace Database\Seeders;

use App\Models\Medication;
use Illuminate\Database\Seeder;

class MedicationSeeder extends Seeder
{
    /**
     * Run the database seeds - Pre-populate with common dental medications
     */
    public function run(): void
    {
        $medications = [
            // ANTIBIOTICS
            [
                'name' => 'Amoxicillin',
                'generic_name' => 'Amoxicillin',
                'category' => 'Antibiotic',
                'description' => 'Broad-spectrum antibiotic used for dental infections',
                'common_dosages' => ['250mg', '500mg', '1g'],
                'side_effects' => 'Nausea, diarrhea, allergic reactions (rash, itching)',
                'contraindications' => 'Penicillin allergy',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Metronidazole',
                'generic_name' => 'Metronidazole',
                'category' => 'Antibiotic',
                'description' => 'Antibiotic effective against anaerobic bacteria in dental infections',
                'common_dosages' => ['200mg', '400mg', '500mg'],
                'side_effects' => 'Metallic taste, nausea, headache',
                'contraindications' => 'Alcohol consumption, pregnancy (first trimester)',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Azithromycin',
                'generic_name' => 'Azithromycin',
                'category' => 'Antibiotic',
                'description' => 'Macrolide antibiotic for dental infections',
                'common_dosages' => ['250mg', '500mg'],
                'side_effects' => 'Stomach upset, diarrhea',
                'contraindications' => 'Liver disease, macrolide allergy',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Cefixime',
                'generic_name' => 'Cefixime',
                'category' => 'Antibiotic',
                'description' => 'Third-generation cephalosporin antibiotic',
                'common_dosages' => ['200mg', '400mg'],
                'side_effects' => 'Diarrhea, nausea, abdominal pain',
                'contraindications' => 'Cephalosporin allergy',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Clindamycin',
                'generic_name' => 'Clindamycin',
                'category' => 'Antibiotic',
                'description' => 'Antibiotic for dental infections, alternative to penicillin',
                'common_dosages' => ['150mg', '300mg', '600mg'],
                'side_effects' => 'Diarrhea, pseudomembranous colitis',
                'contraindications' => 'Severe liver disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // PAINKILLERS
            [
                'name' => 'Ibuprofen',
                'generic_name' => 'Ibuprofen',
                'category' => 'Painkiller (NSAID)',
                'description' => 'Non-steroidal anti-inflammatory drug for pain and inflammation',
                'common_dosages' => ['200mg', '400mg', '600mg'],
                'side_effects' => 'Stomach upset, gastritis, dizziness',
                'contraindications' => 'Active peptic ulcer, severe kidney disease, aspirin allergy',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Paracetamol (Acetaminophen)',
                'generic_name' => 'Paracetamol',
                'category' => 'Painkiller (Analgesic)',
                'description' => 'Pain reliever and fever reducer',
                'common_dosages' => ['500mg', '650mg', '1g'],
                'side_effects' => 'Rare at therapeutic doses, liver toxicity with overdose',
                'contraindications' => 'Severe liver disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Diclofenac',
                'generic_name' => 'Diclofenac Sodium',
                'category' => 'Painkiller (NSAID)',
                'description' => 'Strong anti-inflammatory and analgesic',
                'common_dosages' => ['50mg', '75mg', '100mg'],
                'side_effects' => 'Gastric irritation, dizziness, headache',
                'contraindications' => 'Peptic ulcer, severe heart disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Aceclofenac',
                'generic_name' => 'Aceclofenac',
                'category' => 'Painkiller (NSAID)',
                'description' => 'NSAID for dental pain and inflammation',
                'common_dosages' => ['100mg', '200mg'],
                'side_effects' => 'Nausea, dizziness, diarrhea',
                'contraindications' => 'Peptic ulcer, severe renal impairment',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Tramadol',
                'generic_name' => 'Tramadol Hydrochloride',
                'category' => 'Painkiller (Opioid)',
                'description' => 'Moderate to severe pain relief',
                'common_dosages' => ['50mg', '100mg'],
                'side_effects' => 'Dizziness, drowsiness, nausea, constipation',
                'contraindications' => 'Respiratory depression, severe liver or kidney disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // ANTI-INFLAMMATORY
            [
                'name' => 'Serratiopeptidase',
                'generic_name' => 'Serratiopeptidase',
                'category' => 'Anti-inflammatory (Enzyme)',
                'description' => 'Reduces swelling and inflammation after dental procedures',
                'common_dosages' => ['5mg', '10mg'],
                'side_effects' => 'Rare: nausea, diarrhea',
                'contraindications' => 'Bleeding disorders',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Trypsin + Chymotrypsin',
                'generic_name' => 'Trypsin Chymotrypsin',
                'category' => 'Anti-inflammatory (Enzyme)',
                'description' => 'Proteolytic enzyme for reducing post-operative swelling',
                'common_dosages' => ['100000 AU'],
                'side_effects' => 'Mild gastric discomfort',
                'contraindications' => 'Bleeding disorders, peptic ulcer',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // MOUTH RINSES & TOPICAL
            [
                'name' => 'Chlorhexidine Mouthwash (0.2%)',
                'generic_name' => 'Chlorhexidine Gluconate',
                'category' => 'Antiseptic Mouthwash',
                'description' => 'Antibacterial mouthwash for oral hygiene',
                'common_dosages' => ['0.12%', '0.2%'],
                'side_effects' => 'Tooth staining, altered taste',
                'contraindications' =>  'Hypersensitivity to chlorhexidine',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Povidone Iodine Gargle',
                'generic_name' => 'Povidone Iodine',
                'category' => 'Antiseptic Gargle',
                'description' => 'Antiseptic for oral infections',
                'common_dosages' => ['2%'],
                'side_effects' => 'Rare: allergic reactions, thyroid dysfunction with prolonged use',
                'contraindications' => 'Thyroid disorders, iodine allergy',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Benzydamine Hydrochloride Oral Rinse',
                'generic_name' => 'Benzydamine',
                'category' => 'Analgesic Mouthwash',
                'description' => 'Analgesic and anti-inflammatory mouthwash',
                'common_dosages' => ['0.15%'],
                'side_effects' => 'Numbness, burning sensation',
                'contraindications' => 'Hypersensitivity',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // ANTIFUNGAL
            [
                'name' => 'Fluconazole',
                'generic_name' => 'Fluconazole',
                'category' => 'Antifungal',
                'description' => 'Antifungal for oral candidiasis',
                'common_dosages' => ['50mg', '100mg', '150mg'],
                'side_effects' => 'Nausea, headache',
                'contraindications' => 'Severe liver disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Clotrimazole Oral Gel',
                'generic_name' => 'Clotrimazole',
                'category' => 'Antifungal (Topical)',
                'description' => 'Topical antifungal for oral thrush',
                'common_dosages' => ['1%'],
                'side_effects' => 'Mild irritation',
                'contraindications' => 'Hypersensitivity to clotrimazole',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // ANTIHISTAMINES (for allergic reactions)
            [
                'name' => 'Cetirizine',
                'generic_name' => 'Cetirizine Hydrochloride',
                'category' => 'Antihistamine',
                'description' => 'Antihistamine for allergic reactions',
                'common_dosages' => ['5mg', '10mg'],
                'side_effects' => 'Drowsiness, dry mouth',
                'contraindications' => 'Severe kidney disease',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // VITAMINS & SUPPLEMENTS
            [
                'name' => 'Vitamin B Complex',
                'generic_name' => 'B-Complex Vitamins',
                'category' => 'Vitamin',
                'description' => 'For healing and nerve health after dental procedures',
                'common_dosages' => ['1 tablet'],
                'side_effects' => 'Rare: nausea',
                'contraindications' => 'None significant',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
            [
                'name' => 'Calcium + Vitamin D3',
                'generic_name' => 'Calcium Carbonate + Cholecalciferol',
                'category' => 'Supplement',
                'description' => 'For bone and tooth health',
                'common_dosages' => ['500mg + 250 IU'],
                'side_effects' => 'Constipation, bloating',
                'contraindications' => 'Hypercalcemia, kidney stones',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],

            // CORTICOSTEROIDS
            [
                'name' => 'Prednisolone',
                'generic_name' => 'Prednisolone',
                'category' => 'Corticosteroid',
                'description' => 'Anti-inflammatory for severe dental swelling',
                'common_dosages' => ['5mg', '10mg', '20mg'],
                'side_effects' => 'Increased appetite, insomnia, mood changes',
                'contraindications' => 'Active infections, diabetes',
                'manufacturer' => 'Various',
                'is_active' => true,
            ],
        ];

        foreach ($medications as $medication) {
            Medication::create($medication);
        }
    }
}
