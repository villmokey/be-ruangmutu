<?php

namespace App\Models\Table;

use App\Models\Entity\DocumentRelated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRelatedTable extends DocumentRelated
{
    public function related()
    {
        return $this->belongsTo(DocumentTable::class, 'related_document_id');
    }
}
