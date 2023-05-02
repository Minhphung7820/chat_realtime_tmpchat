<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class ChenTN extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [2,3];
        for ($i=1; $i <=100 ; $i++) { 
           DB::table("messages")->insert([
            ['user_id'=>Arr::random($user),'conversation_id'=>1,'message'=>rand().time().uniqid(),'created_at'=>now()]
           ]);
        }
    }
}
