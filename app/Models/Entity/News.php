<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends AppModel
{
    use SoftDeletes;

    protected $table    =   'news';

    protected $fillable =   [
        'title_id',
        'title_en',
        'title_norsk',
        'desc_id',
        'desc_en',
        'desc_norsk',
        'is_publish'
    ];
}
