<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerComplaint extends AppModel
{
    protected $table    =   'customer_complaints';

    protected $fillable =   [
        'program_id',
        'health_service_id',
        'report',
        'source',
        'note',
        'complaint_id',
        'reported_by',
        'follow_up',
        'complaint_date',
        'coordination',
        'clarification_date',
        'is_public',
        'status',
        'created_id',
        'updated_by',
    ];
}
