<?php

namespace App\Models\Table;

use App\Models\Entity\EventProgram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventProgramTable extends EventProgram
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id', 'id')->select(['id', 'name', 'color']);
    }

    public function document()
    {
        return $this->belongsTo(DocumentTable::class, 'document_id', 'id');
    }
}
