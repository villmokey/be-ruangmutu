<?php

namespace App\Models\Table;

use App\Models\Entity\DocumentProgram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentProgramTable extends DocumentProgram
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class);
    }
}
