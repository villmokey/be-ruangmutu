<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventDocument extends AppModel
{
    use SoftDeletes;

    protected $table    =   'event_documents';

    protected $fillable =   [
        'event_id',
        'document_id',
    ];
}
