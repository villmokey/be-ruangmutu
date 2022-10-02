<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends AppModel
{
    protected $table    =   'positions';

    protected $fillable =   [
        'name',
        'is_leader',
        'created_id',
    ];
}
