<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentProgram extends AppModel
{
    protected $table    =   'document_programs';

    protected $fillable =   [
        'document_id',
        'program_id'
    ];
}
