<?php

namespace App\Models\Table;

use App\Models\Entity\Program;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramTable extends Program
{
    public function subPrograms()
    {
        return $this->hasMany(SubProgramTable::class, 'program_id');
    }
}
