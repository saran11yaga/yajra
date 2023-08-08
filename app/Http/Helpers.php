<?php
use Illuminate\Support\Facades\DB;

function getAllHobbies(){
    $data = DB::table('hobbies')->select('id','hobbie_name')->get();
    return $data;
}

function getUserHobbyIds($user_id){
    $resp = [];
    $rec =  DB::table('user_hobbies')->select('hobbie_id')->where('user_id',$user_id)->get();
    foreach ($rec as $key => $value) {
        $resp[] = $value->hobbie_id;
    }
    return $resp;
}