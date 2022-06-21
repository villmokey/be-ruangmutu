<?php

namespace App\Models\Table;

use App\Models\Entity\DocumentRelated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRelatedTable extends DocumentRelated
{
    public function document()
    {
        return $this->belongsTo(Document::class, 'document_id');
    }

    public function file()
    {
        return $this->belongsTo(Document::class, 'related_document_id');
    }
}
