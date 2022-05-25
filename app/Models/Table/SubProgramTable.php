<?php

namespace App\Models\Table;

use App\Models\Entity\SubProgram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubProgramTable extends SubProgram
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id');
    }
}
