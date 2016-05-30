<?php

use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
      DB::table('testimonial')->truncate();
      $date = new DateTime;
      $testimonial = array(
        ['service_id'=> 1, 'author_name'=> 'Beauty Service','description'=>'I love everything about their service, right from warm greetings to delivery of a service. The beauticians are professional, orderly, and expert. They are doing a great JOB! ', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],

        ['service_id'=> 1, 'author_name'=> 'Pest Control Service','description'=>'MyGharSeva pest control guys assured me of safety of their products. I am very happy with the service and will surely take again next year.', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],

        ['service_id'=> 1, 'author_name'=> 'AC Repair','description'=>'The technician that came to my house to repair my AC was very professional and knowledgeable. He came on time and did the repair work without bothering me for anything as he bought everything with himself!', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],

        ['service_id'=> 1, 'author_name'=> 'Carpenter Service','description'=>'Experts gave a second life to my king-size bed. MyGharSeva provides quality service with reasonable prices! I am very happy and satisfied with the service & recommending to others too!', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],

        ['service_id'=> 1, 'author_name'=> 'Home Cleaning Service','description'=>'I used home cleaning service after New Year party. I am very happy with cleaning they did and will surely recommend others to take it! They stand by their motto- One call we clean it all!', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],

        ['service_id'=> 1, 'author_name'=> 'Plumbing Service','description'=>'It is difficult to get plumbers or carpenters for small work. I was looking for reliable provider who can help me. Now I can rely on MyGharseva', 'page_type'=>'Home','created_by'=> 1, 'updated_by'=> 1, 'created_at'=> $date, 'updated_at'=> $date],
        );

        DB::table('testimonial')->insert($testimonial);
    }
}
