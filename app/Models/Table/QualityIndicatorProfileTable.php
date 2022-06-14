<?php

namespace App\Models\Table;

use App\Models\Entity\QualityIndicatorProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QualityIndicatorProfileTable extends QualityIndicatorProfile
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id')->select('id', 'name');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgramTable::class, 'sub_program_id')->select('id', 'name');
    }

    public function pic()
    {
        return $this->belongsTo(UserTable::class, 'pic_id')->select('id', 'nip', 'name');
    }

    public function createdBy()
    {
        return $this->belongsTo(UserTable::class, 'created_by')->select('id', 'nip', 'name');
    }

    public function document()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function signature()
    {
        return $this->hasMany(QualityIndicatorProfileSignatureTable::class, 'profile_id')->with('user:id,nip,name')->select('id', 'profile_id', 'user_id', 'signed', 'level');
    }

    public function qualityDimension()
    {
        return $this->hasMany(QualityIndicatorProfileDimensionTable::class, 'profile_id')->select('id', 'name', 'profile_id');
    }

    public function indicatorType()
    {
        return $this->hasMany(QualityIndicatorProfileTypeTable::class, 'profile_id')->select('id', 'name', 'profile_id');
    }

    public function dataFrequency()
    {
        return $this->hasMany(QualityIndicatorProfileDataFrequencyTable::class, 'profile_id')->select('id', 'name', 'profile_id');
    }

    public function dataPeriod()
    {
        return $this->hasMany(QualityIndicatorProfileDataPeriodTable::class, 'profile_id')->select('id', 'name', 'profile_id');
    }

    public function analystPeriod()
    {
        return $this->hasMany(QualityIndicatorProfileAnalystPeriodTable::class, 'profile_id')->select('id', 'name', 'profile_id');
    }
}
