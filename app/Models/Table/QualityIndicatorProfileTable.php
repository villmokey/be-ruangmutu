<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileTable extends QualityIndicatorProfile
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgramTable::class, 'sub_program_id');
    }

    public function pic()
    {
        return $this->belongsTo(UserTable::class, 'pic_id');
    }
}
