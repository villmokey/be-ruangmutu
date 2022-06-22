<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends AppModel
{
    use SoftDeletes;

    protected $table    =   'events';

    protected $fillable =   [
        'name',
        'slug',
        'start_date',
        'end_date'
    ];
}
