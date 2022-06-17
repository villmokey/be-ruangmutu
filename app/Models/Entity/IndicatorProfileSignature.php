<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class IndicatorProfileSignature extends AppModel
{
    use SoftDeletes;

    protected $table    =   'indicator_profile_signatures';

    protected $fillable =   [
        'indicator_profile_id',
        'user_id',
        'level',
        'signed',
    ];
}
