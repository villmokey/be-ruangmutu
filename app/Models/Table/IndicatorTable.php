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

    public function profileIndicator()
    {
        return $this->belongsTo(IndicatorProfileTable::class, 'title')->select('id', 'title');
    }

    public function document()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function signature()
    {
        return $this->hasMany(IndicatorSignatureTable::class, 'indicator_id')->with('user:id,nip,name')->select('id', 'indicator_id', 'user_id', 'signed', 'level', 'signed_at');
    }

    public function firstPic()
    {
        return $this->belongsTo(UserTable::class, 'first_pic_id')->select('id', 'nip', 'name');
    }

    public function secondPic()
    {
        return $this->belongsTo(UserTable::class, 'second_pic_id')->select('id', 'nip', 'name');
    }

    public function assignBy()
    {
        return $this->belongsTo(UserTable::class, 'assign_by')->select('id', 'nip', 'name');
    }
}
