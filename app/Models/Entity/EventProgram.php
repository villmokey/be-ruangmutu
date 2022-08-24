<?php

namespace App\Models\Entity;

use App\Models\AppModel;

class EventProgram extends AppModel
{
    protected $table    =   'event_programs';

    protected $fillable =   [
        'event_id',
        'program_id'
    ];
}
