<?php

namespace App\Models\Table;

use App\Models\Entity\OperationalStandard;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperationalStandardTable extends OperationalStandard
{
    public function flowDiagramUrl() {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function histories() {
        return $this->hasMany(OperationalStandardHistoryTable::class, 'operational_standard_id');
    }
}
