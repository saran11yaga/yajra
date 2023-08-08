<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // if(DB::table('users')->count() == 0){

        //     DB::table('users')->insert([

        //         [
        //             'first_name' => 'Saranya',
        //             'last_name' => 'Ganeshan',
        //             'created_at' => date('Y-m-d H:i:s'),
        //             'updated_at' => date('Y-m-d H:i:s')
        //         ]
        //     ]);
            
        // } 

        if(DB::table('hobbies')->count() == 0){

            DB::table('hobbies')->insert([

                [
                    'hobbie_name' => 'Reading Books',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'hobbie_name' => 'Browsing Net',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'hobbie_name' => 'Playing games',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ],
                [
                    'hobbie_name' => 'Playing Music',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ]

            ]);
            
        } 
    }
}
