<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends AppModel
{
    use SoftDeletes;

    protected $table    =   'documents';

    protected $fillable =   [
        'name',
        'slug',
        'document_type_id',
        'program_id'
    ];
}
