<?php

namespace App\Models\Table;

use App\Models\Entity\IndicatorProfile;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorProfileTable extends IndicatorProfile
{
    public function program()
    {
        return $this->belongsTo(ProgramTable::class, 'program_id')->select('id', 'name');
    }

    public function subProgram()
    {
        return $this->belongsTo(SubProgramTable::class, 'sub_program_id')->select('id', 'name');
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

    public function document()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function signature()
    {
        return $this->hasMany(IndicatorProfileSignatureTable::class, 'indicator_profile_id')->with('user:id,nip,name')->select('id', 'indicator_profile_id', 'user_id', 'signed', 'level', 'signed_at');
    }

    public function qualityDimension()
    {
        return $this->hasMany(IndicatorProfileDimensionTable::class, 'indicator_profile_id')->select('id', 'name', 'indicator_profile_id');
    }

    public function indicatorType()
    {
        return $this->hasMany(IndicatorProfileTypeTable::class, 'indicator_profile_id')->select('id', 'name', 'indicator_profile_id');
    }

    public function dataFrequency()
    {
        return $this->hasMany(IndicatorProfileDataFrequencyTable::class, 'indicator_profile_id')->select('id', 'name', 'indicator_profile_id');
    }

    public function dataPeriod()
    {
        return $this->hasMany(IndicatorProfileDataPeriodTable::class, 'indicator_profile_id')->select('id', 'name', 'indicator_profile_id');
    }

    public function analystPeriod()
    {
        return $this->hasMany(IndicatorProfileAnalystPeriodTable::class, 'indicator_profile_id')->select('id', 'name', 'indicator_profile_id');
    }

    public function indicator()
    {
        return $this->hasMany(IndicatorTable::class, 'title', 'id');
    }
}
