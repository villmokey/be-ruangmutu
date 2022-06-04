<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorTable extends QualityIndicator{

    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id')->select('id', 'name');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgramTable::class, 'sub_program_id')->select('id', 'name');
    }

    public function qualityGoal()
    {
        return $this->belongsTo(QualityIndicatorProfileTable::class, 'quality_goal_id')->select('id', 'title');
    }

    public function document()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function signature()
    {
        return $this->hasMany(QualityIndicatorSignatureTable::class, 'indicator_id')->with('user:id,nip,name')->select('id', 'indicator_id', 'user_id', 'signed', 'level');
    }

}
