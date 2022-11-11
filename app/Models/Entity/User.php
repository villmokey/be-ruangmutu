<?php

namespace App\Models\Entity;

use App\Models\AppAuthenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Table\PositionTable;

class User extends AppAuthenticatable
{
    use HasRoles;
    use SoftDeletes;

    protected $table    =   'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'status',
        'position_id',
        'signature_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function position()
    {
        return $this->belongsTo(PositionTable::class, 'position_id');
    }
}
