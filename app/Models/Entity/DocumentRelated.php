<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentRelated extends AppModel
{
    use SoftDeletes;

    protected $table    =   'document_relateds';

    protected $fillable =   [
        'document_id',
        'related_document_id',
    ];
}
