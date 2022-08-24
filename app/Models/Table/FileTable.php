<?php

namespace App\Models\Table;

use App\Models\Entity\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileTable extends File
{
    public function fileable()
    {
        return $this->morphTo();
    }

    public function getHalooAttribute () {
        return 'HALOOO-'. $this->file_dir;
    }
}
