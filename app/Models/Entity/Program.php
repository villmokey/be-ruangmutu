<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Program extends AppModel
{
    use SoftDeletes;

    protected $table    =   'programs';

    protected $fillable =   [
        'pic_id',
        'name',
        'is_publish',
        'color'
    ];
}
