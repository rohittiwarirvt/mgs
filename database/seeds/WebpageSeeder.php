<?php

use Illuminate\Database\Seeder;

class WebpageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    		DB::table('webpage')->truncate();


    		$webpage = array(


    			['friendly_url' => 'home', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '1', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'help-center/', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '2', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'contact-us', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '3', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'about-us', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '4', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'partner-with-us', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '5', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'terms-conditions', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '6', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'privacy', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '7', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'login', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '8', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'register', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '9', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/appliance-repair', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '10', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/beauty', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '11', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/movers-packers', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '12', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/pest-control', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '13', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/plumbing', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '14', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/electrician', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '15', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/home-cleaning', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '16', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/pooja', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '17', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/carpenter', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '18', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/painting', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '19', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/interior-services', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '20', 'updated_at' => new DateTime, 'created_at' => new DateTime],

    			['friendly_url' => 'service/driver-on-demand', 'created_by' => 'admin', 'updated_by' => 'admin', 'is_active' => '1', 'webpage_id' => '21', 'updated_at' => new DateTime, 'created_at' => new DateTime],



    			);

    		DB::table('webpage')->insert($webpage);
    }
}
