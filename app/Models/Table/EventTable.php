<?php

namespace App\Models\Table;

use App\Models\Entity\Event;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventTable extends Event
{
    public function relatedFile()
    {
        return $this->hasMany(EventDocumentTable::class, 'event_id');
    }
}
