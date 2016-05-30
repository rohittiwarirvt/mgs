<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         Eloquent::unguard();
         DB::statement('SET FOREIGN_KEY_CHECKS=0;');
         $this->call(ExcelImporter::class);
         $this->call(RolesAndPermissions::class);
         $this->call(CountryStateCityTableSeeder::class);
         $this->call(QuoteTableSeeder::class);
         $this->call(FaqSeeder::class);
         $this->call(TestimonialSeeder::class);
         $this->call(QuoteSeeder::class);
         $this->call(DepartmentSubjectTableSeeder::class);
         $this->call(FeatureSeeder::class);
         $this->call(EmailTemplateSeeder::class);
         $this->call(SeoSeeder::class);
         $this->call(WebpageSeeder::class);
          DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
