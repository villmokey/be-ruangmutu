<?php

namespace App\Models\Table;

use App\Models\Entity\Event;
use App\Models\Entity\EventProgram;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTable extends Event
{
    public function relatedFile()
    {
        return $this->hasMany(EventDocumentTable::class, 'event_id');
    }

    public function relatedProgram()
    {
        return $this->hasMany(EventProgramTable::class, 'event_id');
    }

    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id');
    }

    public function user()
    {
        return $this->belongsTo(UserTable::class, 'created_id');
    }

    public function otherFiles()
    {
        return $this->morphMany(FileTable::class, 'fileable');
    }
}
