<?php

namespace App\Models\Table;

use App\Models\Entity\DocumentType;
use App\Models\Table\FileTable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTypeTable extends DocumentType
{
    public function thumbnail()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }
}
