<?php

namespace App\Models\Entity;

use App\Models\AppModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends AppModel
{
    protected $table    = 'password_resets';

    protected $dates    =   [
        'created_at'
    ];

    protected $fillable = [
        'email',
        'token',
    ];

    public function getCreatedAtAttribute(): string
    {
        return \Carbon\Carbon::parse($this->attributes['created_at'])->translatedFormat('d M Y H:i:s');
    }
}
