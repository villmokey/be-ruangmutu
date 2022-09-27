<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceUnit extends AppModel
{
    protected $table    =   'service_units';

    protected $guarded =   ['id'];
}
