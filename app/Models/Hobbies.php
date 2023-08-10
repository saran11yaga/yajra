<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Hobbies extends Model
{
    use HasFactory;

    public function users(){
        return $this->belongsToMany(User::class,'user_hobbies','hobbie_id','user_id');
    }
}
