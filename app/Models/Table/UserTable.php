<?php

namespace App\Models\Table;

use App\Models\Entity\User;
use App\Models\Entity\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTable extends User
{
    public function signature()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function pic()
    {
        return $this->hasOne(ProgramTable::class, 'pic_id');
    }

    public function userPosition()
    {
        return $this->hasOne(PositionTable::class, 'position_id', 'id');
    }
}
