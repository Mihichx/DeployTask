<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name'])]
class UserStatus extends Model
{
    public $timestamps = false;
    
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'user_status_id');
    }
}
