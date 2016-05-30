<?php

use Illuminate\Database\Seeder;

class CountryStateCityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Insert Country
        DB::table('country')->delete();
        DB::table('country')->insert(
        	['name' => 'India', 'is_active' => 1, 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1, 'updated_by' => 1]
        );

        //Insert States
        DB::table('state')->delete();
        DB::table('state')->insert(
        	 ['name' => 'Maharashtra', 'country_id' => 1, 'is_active' => 1, 'created_at' => new DateTime, 'updated_at' => new DateTime, 'created_by' => 1, 'updated_by' => 1]
        );

        //Insert Cities
        DB::table('city')->delete();
        $cities = array(
            ['name' => 'Pune', 'state_id' => 1, 'is_active' => 1,  'created_by' => 1, 'updated_by' => 1],
        	['name' => 'Mumbai', 'state_id' => 1, 'is_active' => 0, 'created_by' => 1, 'updated_by' => 1],
        	['name' => 'Pimpri-Chinchwad', 'state_id' => 1, 'is_active' => 0, 'created_by' => 1, 'updated_by' => 1]
        );

      	foreach ($cities as  $city) {
            DB::table('city')->insert([
                    'name' => $city['name'],
                    'state_id' =>  $city['state_id'],
                    'is_active' =>  $city['is_active'],
                    'created_at' => new DateTime,
                    'updated_at' => new DateTime,
                    'created_by' => $city['created_by'],
                    'updated_by' => $city['updated_by']
            ]);
        }
    }
}
