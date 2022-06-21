<?php

namespace App\Models\Table;

use App\Models\Entity\Document;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTable extends Document
{
    public function file()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function documentType()
    {
        return $this->belongsTo(DocumentTypeTable::class);
    }

    public function program()
    {
        return $this->belongsTo(ProgramTable::class);
    }

    public function relatedFile()
    {
        return $this->hasMany(DocumentRelatedTable::class, 'document_id');
    }
}
