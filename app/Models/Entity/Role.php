<?php

namespace App\Models\Entity;

use Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot'
    ];

}
