<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class QuoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
      DB::table('status')->truncate();
      DB::table('quote_sources')->truncate();
      DB::table('quotes')->truncate();
      DB::table('shopping_materials')->truncate();
      DB::table('shopping_cart_items')->truncate();
      $status = [
                  ['Quote Under Processing', 'New', 'Lorem Ipsum',1],
                  ['Quote Under Processing', 'Quote Under Processing (By Rep)', 'Lorem Ipsum',2],
                  ['Quote Under Processing', 'Quote Under Processing (By Manager)', 'Lorem Ipsum',3],
                  ['Quote Rejected', 'Rejected (by Manager)', 'Lorem Ipsum',4],
                  ['Rejected By Me', 'Rejected(by Customer)', 'Lorem Ipsum',5],
                  ['Quote Under Processing', 'Approved', 'Lorem Ipsum',6],
                  ['Quote Expired', 'Expired',  'Lorem Ipsum',7],
                  ['Quote Buy', 'Publish', 'Lorem Ipsum',8],
                  ['Quote Under Processing', 'Re-Submitted', 'Lorem Ipsum',9],
                  ['Quote Under Processing', 'Under Inspection', 'Lorem Ipsum',10],
                  ['Quote Purchased','Purchase','Lorem Ipsum',11]
                ];

       foreach ($status as $key => $value) {
         DB::table('status')->insert([
                    'id' => $value[3],
                    'status_external_name' => $value[0],
                    'status_internal_name' => $value[1],
                    'status_description' =>  $value[2],
                    'is_active' =>  1,
                    'created_at' =>Carbon::now(),
                    'updated_at' =>Carbon::now(),
                ]);
       }


       $quote_source = [['Website', 'Lorem Ipsum'], ['Mobile', 'Lorem Ipsum'],
       ['Assisted Quote', 'Lorem Ipsum']];

       foreach ($quote_source as $key => $value) {
         DB::table('quote_sources')->insert([
                    'quote_source_title' => $value[0],
                    'quote_source_description' =>  $value[1],
                    'is_active' =>  1,
                    'created_at' =>Carbon::now(),
                    'updated_at' =>Carbon::now(),
                ]);
       }
    }
}
