<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CustomModel extends Model
{
    use HasFactory;

    public function getAllUserHobbies(){
        $res = DB::table('user_hobbies as uh')->select('uh.user_id','u.first_name','u.last_name',DB::raw('group_concat(h.hobbie_name) as hobbies_name'),DB::raw('group_concat(uh.hobbie_id) as hobbies_id') )
        ->leftJoin("users as u" ,'uh.user_id','=','u.id')
        ->leftJoin("hobbies as h",'h.id','=','uh.hobbie_id')
        ->groupBy('uh.user_id')
        ->get();
      
        return $res;
    }

    public function getUserHobbies($user_id){
        $res = DB::table('user_hobbies as uh')->select('uh.user_id','u.first_name','u.last_name',DB::raw('group_concat(h.hobbie_name) as hobbies_name'),DB::raw('group_concat(uh.hobbie_id) as hobbies_id') )
        ->leftJoin("users as u" ,'uh.user_id','=','u.id')
        ->leftJoin("hobbies as h",'h.id','=','uh.hobbie_id')
        ->groupBy('uh.user_id')
        ->where('uh.user_id','=',$user_id)
        ->first();
      
        return $res;
    }
}
