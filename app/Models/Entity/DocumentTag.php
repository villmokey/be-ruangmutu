<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentTag extends AppModel
{
    use SoftDeletes;

    protected $table    =   'document_tags';

    protected $fillable =   [
        'name',
        'slug',
        'is_publish'
    ];
}
