<?php

use Illuminate\Database\Seeder;
use App\Models\Feature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\FileEntry;
use App\Models\Service;
use App\Repositories\FileRepository;


class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    protected $file;

    public function __construct(FileRepository $file) {

      $this->file = $file;
    }
    public function run()
    {
        DB::table('features')->truncate();

        $beauty = Service::where('service_name', 'Beauty Services')->first();
        $painting= Service::where('service_name', 'Painting')->first();
        $pest_control= Service::where('service_name', 'Pest Control')->first();
        $cleaning= Service::where('service_name', 'Home Cleaning')->first();
        $app_repair = Service::where('service_name', 'Appliance Repair')->first();
        $plumbing= Service::where('service_name', 'Plumber')->first();
        $electrician = Service::where('service_name', 'Electrician')->first();
        $carpenter = Service::where('service_name', 'Carpenter')->first();
        $interiors= Service::where('service_name', 'Interior Designer')->first();
        $pooja= Service::where('service_name', 'Pooja Services')->first();
        $drivers= Service::where('service_name', 'Driver on Demand')->first();
        $movers= Service::where('service_name', 'Movers & Packers')->first();


        //Beauty files
        $files_options = ['disk' => 'mgspublic', 'folder'=> '/files/feature'];
        $facial_file_id = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-beauty-facial.png', $files_options, true);
        $hair_file_id = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-beauty-hair-color.png', $files_options, true);
        $manicure_file_id = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-beauty-manicure.png', $files_options, true);
        $waxing_file_id = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-beauty-waxing.png', $files_options, true);

        //Drivers
        $drivers_within = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-driver-on-demand-within-city.png', $files_options, true);
        $drivers_outside = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-driver-on-demand-outside-city.png', $files_options, true);


        //Carpenter

        $door = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-carpenter-door.png', $files_options, true);
        $furniture = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-carpenter-furniture.png', $files_options, true);
        $installation = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-carpenter-installation.png', $files_options, true);
        $window = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-carpenter-window.png', $files_options, true);
        $other_carp=$this->file->createFile("./database/seeds/featureimages/" . 'mgs-carpenter-window.png', $files_options, true);

        //electrician

        $geyser_repair = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-electrician-geyser-repair.png', $files_options, true);
        $inverter_repair = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-electrician-inverter-repair.png', $files_options, true);
        $rewiring = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-electrician-rewiring.png', $files_options, true);
        $tube_light = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-electrician-tube-light-fan-repair.png', $files_options, true);

        //cleaning

        $home_cleaning = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-home-cleaning-bathroom.png', $files_options, true);
        $bedroom = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-home-cleaning-bedroom.png', $files_options, true);
        $kitchen = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-home-cleaning-kitchen.png', $files_options, true);
        $general = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-home-cleaning-general-areas.png', $files_options, true);


        //interiors
        $interior_furniture = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-interior-furniture.png', $files_options, true);
        $home = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-interior-home-improvement.png', $files_options, true);
        $int_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-interior-painting-sculptures.png', $files_options, true);
        $wall_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-interior-wall-painting.png', $files_options, true);

        //movers

        $outside_city = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-movers-packers-outside-city.png', $files_options, true);
        $within_city = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-movers-packers-within-city.png', $files_options, true);

        //painting

        $customized_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-customized-art.png', $files_options, true);
        $exterior_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-exterior-paint.png', $files_options, true);
        $interior_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-interior-paint.png', $files_options, true);
        $other_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-other.png', $files_options, true);
        $water_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-water-proofing.png', $files_options, true);
        $texture_painting = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-painting-texture-paint.png', $files_options, true);

        //var_dump($pest_control['id']);

        //pest Control

        $ants = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pest-control-ants.png', $files_options, true);
        $bed_bugs = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pest-control-bed-bugs.png', $files_options, true);
        $cockroach = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pest-control-cockroach.png', $files_options, true);
        $other_pest = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pest-control-other.png', $files_options, true);

        //plumbing

        $bathroom = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-plumbing-bathroom.png', $files_options, true);
        $commercial = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-plumbing-commercial.png', $files_options, true);
        $kitchen = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-plumbing-kitchen.png', $files_options, true);
        $toilet = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-plumbing-toilet.png', $files_options, true);

        //pooja
        $festive = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pooja-service-festive.png', $files_options, true);
        $marriage = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pooja-service-marriage-ceremony.png', $files_options, true);
        $special = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pooja-service-special-pooja.png', $files_options, true);
        $vastu = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-pooja-service-vastu-shanti.png', $files_options, true);

        //appliance repair

        $ac = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-ac.png', $files_options, true);
        $laptop = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-laptop-tab.png', $files_options, true);
        $microwave = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-microwave.png', $files_options, true);
        $mobile = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-mobile.png', $files_options, true);
        $refrigerator = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-refrigerator.png', $files_options, true);
        $tv = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-tv.png', $files_options, true);
        $washing_machine = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-washing-machine.png', $files_options, true);
        $other_repair = $this->file->createFile("./database/seeds/featureimages/" . 'mgs-repair-other.png', $files_options, true);



    $features = array(
        //1.appliance repair
                ['file_id' => $ac['id'], 'service_id' => $app_repair['id'], 'title' => 'AC Repair', 'description' => 'Let it be maintenance, installation or any other problem, our experts provide instant solutions to fix your AC!', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $laptop['id'], 'service_id' => $app_repair['id'], 'title' => 'Laptop/Tab Repair', 'description' => 'Hardware issue, software, battery or virus issue our skilled technician will fix everything.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $mobile['id'], 'service_id' => $app_repair['id'], 'title' => 'Mobile Repair', 'description' => 'Any model any phone, our experts know it all. Screen repair, water damaged, Battery fix, we do it all.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $tv['id'], 'service_id' => $app_repair['id'], 'title' => 'TV Repair', 'description' => 'Our expert has knowledge to fix issues with LCD, LED, Plasma or 3D TV and work to deliver the best.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $washing_machine['id'], 'service_id' => $app_repair['id'], 'title' => 'Washing Machine Repair', 'description' => 'Motor Issue, Spin issue or Timer issue? Front load, Top Load, dryer or semi-automatic, we all fix everything.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $microwave['id'], 'service_id' => $app_repair['id'], 'title' => 'Microwave Repair', 'description' => 'Our technician will repair common issues with your microwave. Turntable repair, Coil issue, PCB repair', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $refrigerator['id'], 'service_id' => $app_repair['id'], 'title' => 'Refrigerator Repair', 'description' => 'Trained experts will repair simple or complex problem like water leakage, Gas charging, Thermostat repair.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $other_repair['id'], 'service_id' => $app_repair['id'], 'title' => 'Other Appliance Repair', 'description' => 'Service you are looking for is not listed here? Call us, we provide repair of all home appliances.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//2. Beauty

                ['file_id' => $facial_file_id['id'], 'service_id' => $beauty['id'], 'title' => 'Facial', 'description' => 'Feel like a celebrity at comfort of your home. Get Pampered.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $hair_file_id['id'], 'service_id' => $beauty['id'], 'title' => 'Hair Color', 'description' => 'Be Stylo! Choose a new color for yourself that suits the most. ', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $manicure_file_id['id'], 'service_id' => $beauty['id'], 'title' => 'Manicure/Pedicure', 'description' => 'Refresh your mind & relax your soul with our special Mani/Pedi packages.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' =>$waxing_file_id['id'], 'service_id' => $beauty['id'], 'title' => 'Waxing', 'description' => 'Feel better, Feel fresh with our special waxing services by experts.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//3. Carpenter


                ['file_id' => $furniture['id'], 'service_id' => $carpenter['id'], 'title' => 'Furniture Work', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $door['id'], 'service_id' => $carpenter['id'], 'title' => 'Door Work', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $window['id'], 'service_id' => $carpenter['id'], 'title' => 'Window Work', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $installation['id'], 'service_id' => $carpenter['id'], 'title' => 'Heavy Assembly/Installation of Furniture’s', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//4. Electrician

                ['file_id' => $tube_light['id'], 'service_id' => $electrician['id'], 'title' => 'Tube light Repair', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $geyser_repair['id'], 'service_id' => $electrician['id'], 'title' => 'Geyser Repair', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $inverter_repair['id'], 'service_id' => $electrician['id'], 'title' => 'Inverter Repair', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $rewiring['id'], 'service_id' => $electrician['id'], 'title' => 'Rewiring & Many More', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//5. Movers and Packers

                ['file_id' => $outside_city['id'], 'service_id' => $movers['id'], 'title' => 'Moving within a city', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $within_city['id'], 'service_id' => $movers['id'], 'title' => 'Moving outside a city', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],


//6.Painting
                ['file_id' => $customized_painting['id'], 'service_id' => $painting['id'], 'title' => 'Customized Art', 'description' => 'Trained painters ready to craft an artwork on your walls.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $exterior_painting['id'], 'service_id' => $painting['id'], 'title' => 'Exterior', 'description' => 'Beautify your house! Weather shield, crack resistant paint!', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $interior_painting['id'], 'service_id' => $painting['id'], 'title' => 'Interior Paint', 'description' => 'Add colors to your life. Premium Brands, Expert painters.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $other_painting['id'], 'service_id' => $painting['id'], 'title' => 'Other', 'description' => 'We provide customized solutions as per your needs.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $water_painting['id'], 'service_id' => $painting['id'], 'title' => 'Water proofing', 'description' => 'House leakage is no longer an issue with our modern waterproofing techniques.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $texture_painting['id'], 'service_id' => $painting['id'], 'title' => 'Texture Paint', 'description' => 'Give your house a designer look with special techniques.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//7. Pest Control

                ['file_id' => $cockroach['id'], 'service_id' => $pest_control['id'], 'title' => 'Cockroach & Insects', 'description' => 'We have a safe eco-friendly and result oriented Gel treatment for effective management of cockroaches.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $bed_bugs['id'], 'service_id' => $pest_control['id'], 'title' => 'Bed Bugs', 'description' => ' We have a comprehensive solution for bed bugs, which consist of two service contract and AMC with four services in year.  ', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $ants['id'], 'service_id' => $pest_control['id'], 'title' => 'Termite and Ants', 'description' => 'For termites we have an odorless control treatment which just not repels the termite but kills them. ', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $other_pest['id'], 'service_id' => $pest_control['id'], 'title' => 'Other services', 'description' => 'Rats, lizards or any other ', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//8. Drivers
                ['file_id' => $outside_city['id'], 'service_id' => $drivers['id'], 'title' => 'Driver within a city', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $within_city['id'], 'service_id' => $drivers['id'], 'title' => 'Driver within a city', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],



//9. interiors
                ['file_id' => $interior_furniture['id'], 'service_id' => $interiors['id'], 'title' => 'Interior Decoration', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $home['id'], 'service_id' => $interiors['id'], 'title' => 'Home/Office Improvements', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $int_painting['id'], 'service_id' => $interiors['id'], 'title' => 'Painting Decoration & Sculptures', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $wall_painting['id'], 'service_id' => $interiors['id'], 'title' => 'Wall Paintings', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//10. pooja

                ['file_id' => $festive['id'], 'service_id' => $pooja['id'], 'title' => 'Festive Pooja', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $marriage['id'], 'service_id' => $pooja['id'], 'title' => 'Marriage Ceremony', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $special['id'], 'service_id' => $pooja['id'], 'title' => 'Special Pooja & Others', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $vastu['id'], 'service_id' => $pooja['id'], 'title' => 'Vastu Shanti', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//11. plumbing
                ['file_id' => $bathroom['id'], 'service_id' => $plumbing['id'], 'title' => 'Bathroom Repair ', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $commercial['id'], 'service_id' => $plumbing['id'], 'title' => 'Commercial Plumbing ', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $kitchen['id'], 'service_id' => $plumbing['id'], 'title' => 'Kitchen Repair ', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $toilet['id'], 'service_id' => $plumbing['id'], 'title' => 'Toilet Repair ', 'description' => '', 'created_at' => new DateTime, 'updated_at' => new DateTime],

//12. cleaning

                ['file_id' => $home_cleaning['id'], 'service_id' => $cleaning['id'], 'title' => 'BATHROOM', 'description' => 'Clean, disinfect toilets and tubs, clean sink/basins, tiles, mirrors cleaned and wiped dry.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $bedroom['id'], 'service_id' => $cleaning['id'], 'title' => 'BEDROOM', 'description' => 'Cleaning of floors, walls, vacuuming of the mattress, removing dust, cleaning of mirrors, ceiling fan.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $kitchen['id'], 'service_id' => $cleaning['id'], 'title' => 'KITCHEN', 'description' => 'Cleaning all cabinets, appliances from outside, sink, counter area, mopping floor.', 'created_at' => new DateTime, 'updated_at' => new DateTime],

                ['file_id' => $general['id'], 'service_id' => $cleaning['id'], 'title' => 'GENERAL AREAS', 'description' => 'General living area, carpet, sofas, furniture, windows, doors and window mesh all dusted and cleaned.', 'created_at' => new DateTime, 'updated_at' => new DateTime],
 );

        DB::table('features')->insert($features);
    }


}
