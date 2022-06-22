<?php

namespace App\Models\Table;

use App\Models\Entity\EventDocument;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventDocumentTable extends EventDocument
{
    public function related()
    {
        return $this->belongsTo(DocumentTable::class, 'document_id');
    }
}
