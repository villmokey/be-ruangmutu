<?php

namespace App\Models\Table;

use App\Models\Entity\Indicator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorTable extends Indicator{

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
        return $this->belongsTo(IndicatorProfileTable::class, 'quality_goal_id')->select('id', 'title');
    }

    public function document()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function signature()
    {
        return $this->hasMany(IndicatorSignatureTable::class, 'indicator_id')->with('user:id,nip,name')->select('id', 'indicator_id', 'user_id', 'signed', 'level');
    }

}
