<?php

namespace App\Models\Table;

use App\Models\Entity\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTable extends User
{
    public function signature()
    {
        return $this->morphOne(FileTable::class, 'fileable');
    }

    public function pic()
    {
        return $this->hasOne(ProgramTable::class, 'pic_id');
    }
}
