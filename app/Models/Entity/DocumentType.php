<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentType extends AppModel
{
    use SoftDeletes;

    protected $table    =   'document_types';

    protected $fillable =   [
        'name',
        'is_publish'
    ];
}
