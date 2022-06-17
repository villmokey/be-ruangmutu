<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Entity\Program;
use App\Models\Entity\SubProgram;

class CreateProgramAndSubProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Admen Progam and Sub Program
        $admen = Program::create([
            'name' => 'Admen',
        ]);

        SubProgram::create([
            'name' => 'Mutu',
            'program_id' => $admen->id,
        ]);
        SubProgram::create([
            'name' => 'Perencanaan',
            'program_id' => $admen->id,
        ]);
        SubProgram::create([
            'name' => 'Pengadaan',
            'program_id' => $admen->id,
        ]);
        SubProgram::create([
            'name' => 'Keuangan',
            'program_id' => $admen->id,
        ]);
        SubProgram::create([
            'name' => 'Sarpras',
            'program_id' => $admen->id,
        ]);

        // UKM Progam and Sub Program
        $ukm = Program::create([
            'name' => 'UKM',
        ]);

        SubProgram::create([
            'name' => 'Program Gizi',
            'program_id' => $ukm->id,
        ]);
        SubProgram::create([
            'name' => 'UKS',
            'program_id' => $ukm->id,
        ]);
        SubProgram::create([
            'name' => 'UKGS',
            'program_id' => $ukm->id,
        ]);

        // UKP Progam and Sub Program
        $ukp = Program::create([
            'name' => 'UKP',
        ]);

        SubProgram::create([
            'name' => 'UP Gigi dan Mulut',
            'program_id' => $ukp->id,
        ]);
        SubProgram::create([
            'name' => 'UP Geriatri',
            'program_id' => $ukp->id,
        ]);
        SubProgram::create([
            'name' => 'UP PKPR',
            'program_id' => $ukp->id,
        ]);
        SubProgram::create([
            'name' => 'UP MTBS',
            'program_id' => $ukp->id,
        ]);
        SubProgram::create([
            'name' => 'Laboratorium',
            'program_id' => $ukp->id,
        ]);
    }
}
