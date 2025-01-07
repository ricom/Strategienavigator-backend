<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SwitchLog extends Model
{
    use HasFactory;
    protected $fillable = ['user_id'];


    // Definiere die Beziehung zu User
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Accessor für den Benutzernamen
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->username : 'Unknown User';
    }


}
