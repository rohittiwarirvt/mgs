<?php

use Illuminate\Database\Seeder;
use App\Repositories\ServiceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OptionAndOptionChoiceRepository;
use App\Repositories\AttributeRepository;
use Illuminate\Support\Facades\File;
use App\Models\FileEntry;
use App\Repositories\FileRepository;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    protected $service;
    protected $attribute;
    protected $option_and_choice;
    protected $product;
    protected $file;

    public function __construct(AttributeRepository $attribute, OptionAndOptionChoiceRepository $option_and_choice, ServiceRepository $service, ProductRepository $product, FileRepository $file) {

      $this->service = $service;
      $this->product = $product;
      $this->attribute = $attribute;
      $this->option_and_choice = $option_and_choice;
      $this->file = $file;
    }

    public function run()
    {

      // Services
      DB::table('services')->truncate();
      DB::table('products')->truncate();
      DB::table('attributes')->truncate();
      DB::table('options')->truncate();
      DB::table('files')->truncate();
     // DB::table






      //1 Appliance Repair
        $service["Appliance Repair"]['AC Spilt / Window'] = [['Dry Service', 'Rs.550'],
        ['Wet Service',  'Rs 750'],
        ['Installation', 'Rs.1500'],
        ['Un-Installation', 'Rs.1000'],
        ['Gas filling', 'Rs. 2000 for upto 2 tons'],
        ['Other Repair', 'Upon Inspection'],
        ['AMC of AC Service (4 Times a Year contract period)', 'Rs. 1500']];

        $service["Appliance Repair"]['Washing Machine'] = [['Fully Automatic Front Load', 'Upon Inspection'],
        ['Fully Automatic Top Load',  'Upon Inspection'],
        ['Semi-Automatic', 'Upon Inspection']];

        $service["Appliance Repair"]['Geyser'] = [['Instant Water Geyser', 'Upon Inspection'],
        ['Storage Water Geyser',  'Upon Inspection']];

        $service["Appliance Repair"]['Microwave Oven'] = [['Convection', 'Upon Inspection']];

        $service["Appliance Repair"]['TV Repair'] = [['LED', 'Upon Inspection'],
        ['LCD',  'Upon Inspection'],
        ['Plasma', 'Upon Inspection'],
        ['3D LED', 'Upon Inspection'],
        ['Smart TV', 'Upon Inspection'],
        ['CRT', 'Upon Inspection']];

        $service["Appliance Repair"]['Laptop Repair'] = [['', '']];

        $service["Appliance Repair"]['Refrigerator Repair'] = [['', '']];

        $display["Appliance Repair"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];

        //2 Beauty Service
        $service["Beauty Services"]['Hair Care'] =[['Head Massage', 'Rs 300'],
        ['Head Spa',  'Rs 600 to Rs 2000'],
        ['Hair Cuts Basic', 'Rs 199'],
        ['Hair Cuts Advance', 'Rs.399'],
        ['Head Heena Application as per length of the hair', 'Rs 300 Onwards'],
        ['Coloring', 'Rs 999 Onwards'],
        ['Heena / Hair Color Apply Only (Product provided by customer)', 'Rs 199'],
        ['Highlighting','Rs 999'],
        ['Highlighting Per Strik','Rs 175'],
        ['Hair Treatments Per settings', 'Rs 600'],
        ['Thermal Settings', 'Rs 199 Onwards'],
        ['Rebonding', 'Rs 4200 - Rs 9600'],
        ['Perming', 'Rs 1800 - Rs4800']];

        $service["Beauty Services"]['Skin Care']= [['Clean Up','Rs 420'],
        ['Basic Facial','Rs 600'],
        ['Fruit Facial', 'Rs 720'],
        ['Gold/Silver Facial', 'Rs 1200'],
        ['Chocolate Facial','Rs 720'],
        ['Anti-Ageing Facial', 'Rs 1020'],
        ['De Pigmentation Facial','Rs 1020'],
        ['D-Tann Facial','Rs 840'],
        ['Threading','Rs 48'],
        ['Basic Makeups','Rs 600 - Rs 2400'],
        ['Upper Lip, Chin, Forehead', 'Rs 60'],
        ['Peeling Treatment','Rs 1800 - Rs 3000(per sitting)'],
        ['Bleach','Rs 180 to Rs 360'],
        ['Diamond/Pearl Facial', 'Rs 1200'],
        ['Body Massages', 'Rs 1800'],
        ['Body Polishing', 'Rs 2500 to Rs 3000'],
        ['Spa Treatment', 'Rs 2000']];

        $service["Beauty Services"]['Body Care']= [['Leg Waxing','Rs 300'],
        ['Bikini Waxing','Rs 99'],
        ['Pedicure','Rs 360'],
        ['Advance Pedicure','Rs 600'],
        ['Nail Art','Rs 60(per Tip)'],
        ['Arm Waxing','Rs 300'],
        ['Under Arm Waxing','Rs 60'],
        ['Manicure','Rs 360'],
        ['Advance Manicure', 'Rs 600'],
        ['Full Body Wax', 'Rs 999']];

        $service["Beauty Services"]['Make Up(Including Bridal)']= [['Engagement Makeup(Includes 1 Makeup + Hairstyle + Saree/Dress draping)','Rs 3000'],
        ['Bridal Makeup(Includes 1 Makeup + Hairstyle + Saree/Dress Drapping)','Rs 6000'],
        ['Sider Makeup(Includes 1 Makeup + Hairstyle + Saree/Dress draping)','Rs 1500'],
        ['Saree draping','Rs 300'],
        ['Hairstyle','Rs 300'],
        ['Party Makeup(1 Makeup + Hairstyle + Dress Dreeping)','Rs 1500'],
        ['Fashion Makeup( 1 Makeup + Hairstyle + Dress Dreeping)','Rs 2000'],
        ['Natural Makeup( 1 Makeup + Hairstyle + Dress Dreeping)', 'Rs 1200'],
        ['Red Carpet(1 Makeup + Hairstyle + Dress Dreepingg)', 'Rs 2500'], //regular price not given, so inserted premium price 2500
        ['Glamour(1 Makeup + Hairstyle + Dress Dreeping)','Rs 2000'],
        ['HD(1 Makeup + Hairstyle + Dress Dreepingg)','Rs 4000']];

        $service["Beauty Services"]['Packages']=[['Platinum Package(Head Massage, Body Massage, Manicure, Pedicure, Cleansing (Scrubing))','Rs 1900'],
        ['Silver Package(Cleanup, Full Arm Waxing with under arms, Leg Waxing, Eye brows, Upper Lips + Chin, Eye Massage)','Rs 999'],
        ['Gold Package(Facial, Arm Waxing with Underarms, Face Bleach, Eye brows, Upper Lips + Chin, Eye Massage)','Rs 1299'],
        ['Senior Citizen Package 1(Anti Aging Facial, Color Matt or Heena, Hair Cut, Body Massage, Eye brows, Upper Lips + Chin)','Rs 2246'],
        ['Senior Citizen Package 2(Anti Aging Facial (Mini), Color Touch Up, Hair Cut, Eye Brows & Massage, Back Massage, Hand Massage)','Rs 974'],
        ['Bridal Package For 3 Months Seatings(Full Body Wax, Gold Facial, Gold Bleach, Manicure, Pedicure, Body Policing, Head Spa, 1 Makeup)','Rs 25000']];

        $display["Beauty Services"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];
        //3 movers and packers

        $service["Movers & Packers"]["Movers & Packers"]['1BHK'] = [['Within City', '7000-10000'],
        ['Outside City', '7000-10000']];


        $service["Movers & Packers"]["Movers & Packers"]['2BHK'] = [['Within City', '9000-15000'],
        ['Outside City', '9000-15000']];


        $service["Movers & Packers"]["Movers & Packers"]['3BHK'] = [['Within City', '13000-17000'],
        ['Outside City', '13000-17000']];


        $service["Movers & Packers"]["Movers & Packers"]['4BHK'] = [['Within City', '16000-21000'],
        ['Outside City', '16000-21000']];


        $service["Movers & Packers"]["Movers & Packers"]['5BHK'] = [['Within City', '20000-25000'],
        ['Outside City', '20000-25000']];


       /* $service["Movers & Packers"]["Movers & Packers"]['Others'] = [['Within City', '24000-29000'],
        ['Outside City', '24000-29000']];*/

        $display["Movers & Packers"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];

        //4 Pest Control

        $service["Pest Control"]["Cockroach Control Treatment"] = [['1BHK', '575'],
        ['2BHK', '800'],
        ['3BHK', '900'],
        ['4BHK', '1050'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', 'Upon Inspection']];

        $service["Pest Control"]['Bed Bugs Control Treatment']= [['1BHK', '1500'],
        ['2BHK', '1800'],
        ['3BHK', '2000'],
        ['4BHK', '2200'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', 'Upon Inspection']];


        $service["Pest Control"]['Rodent Control Treatment']= [['1BHK', '500'],
        ['2BHK', '600'],
        ['3BHK', '700'],
        ['4BHK', '800'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', 'Upon Inspection']];

        $service["Pest Control"]['Termite Control Treatment']= [['1BHK', 'Rs.6 Per SQFT'],
        ['2BHK', 'Rs.6 Per SQFT'],
        ['3BHK', 'Rs.6 Per SQFT'],
        ['4BHK', 'Rs.6 Per SQFT'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', 'Upon Inspection']];

        $service["Pest Control"][' Wood Borer Control Treatment']= [['1BHK', 'Upon Inspection'],
        ['2BHK', 'Upon Inspection'],
        ['3BHK', 'Upon Inspection'],
        ['4BHK', 'Upon Inspection'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', 'Upon Inspection']];

        $service["Pest Control"]['Mosquito Control Treatment']= [['1BHK', '700'],
        ['2BHK', '900'],
        ['3BHK', '1100'],
        ['4BHK', '1300'],
        ['Bungalow/Independent House/Villa', 'Upon Inspection'],
        ['Commercial Premises', '']];

        // $display["Pest Control"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];
        // //5 plumbing

        $service["Plumbing"]['Blockage/Leakage Repair'] = [['Flush Tank Repair', 'Upon Inspection'],['Tap (Repair/Replacement)', 'Upon Inspection'],['Faucet(Repair/Replacement)', 'Upon Inspection'],['Mirror Fixing/Toiler Paper Folder Fixing/Soap Dish Holder Fixing', 'Upon Inspection'],['Railing For Diabetic Patients', 'Upon Inspection'],['Convert Indian To Western Toilet','Upon Inspection']];
        // $display["Plumbing"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];

        // //6 Electrician

        $service["Electrician"]['Tube light'] = [['Fan/Exhaust', ''],
        ['Geyser',''],['Inverter', ''],['Switch/Fuse', ''],['Wiring/Rewiring', ''],
        ['Doorbell', 'Upon Inspection'],['Motor/Pump', '']];

        // $display["Electrician"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];

        // //7 cleaning

        $service["Home Cleaning"]['Home Based Cleaning'] = [['Home Deep Cleaning', 'Rs. 4 Per SqFt'], ['Sofa Shampooing, Rs. 300 Per Seat'],
        ['Chair Shampooing','Rs. 75 Per Chair'],
        ['Carpet Shampooing','Rs. 150 Per SqFt'],
        ['Floor Scrubing', 'Rs 2 Per SqFt'],
        ['Car Shampooing', 'Rs 1200'],
        ['Toilet Cleaning','Rs. 500 Per Toilet'],
        ['Facade Cleaning', 'Upon Inspection'],
        ['Blinds', 'Upon Inspection']];


        $service["Home Cleaning"]['Commercial Cleaning'] = [['Tank Cleaning', 'Upon Inspection'],
        ['Fire Services', 'Upon Inspection'],
        ['Floor Deep Cleaning','Upon Inspection'],
        ['CCTV & Cameras', 'Upon Inspection'],
        ['Swimming Pool Cleaning', 'Upon Inspection'],
        ['Facade Cleaning', 'Upon Inspection'],
        ['Gardening', 'Upon Inspection'],
        ['Other Requests', 'Upon Inspection']];

        // $display["Home Cleaning"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];


        // //8 Pooja

        $service["Pooja"]['Abhishek'] = [['Pooja Without Material', 'Rs 500'],
        ['Pooja With Material', 'Rs 500']];


        $service["Pooja"]['Bhumipujan'] = [['Pooja Without Material', 'Rs 1650'],
        ['Pooja With Material', 'Rs 1650']];


        $service["Pooja"]['Ganesh Atharva Shirsha Sahastra Awartan'] = [['Pooja Without Material', 'Rs 5500'],
        ['Pooja With Material', 'Rs 5500']];


        $service["Pooja"]['Ghrihpravesh'] = [['Pooja Without Material', 'Rs 800'],
        ['Pooja With Material', 'Rs 800']];


        $service["Pooja"]['Grahyadna Devak'] = [['Pooja Without Material', 'Rs 1500'],
        ['Pooja With Material', 'Rs 1500']];


        $service["Pooja"]['Jawal'] = [['Pooja Without Material', 'Rs.1300'],
        ['Pooja With Material','Rs.1300']];


        $service["Pooja"]['Kalsarp Shanti'] = [['Pooja Without Material', 'Rs. 4500'],
        ['Pooja With Material', '4500']];


        $service["Pooja"]['Laghurudra'] = [['Pooja Without Material', 'Rs.6500'],
        ['Pooja With Material', 'Rs.6500']];


        $service["Pooja"]['Mangala Gaur Pujan'] = [['Pooja Without Material', 'Rs.600'],
        ['Pooja With Material', 'Rs.600']];

        $service["Pooja"]['Mangala Gaur Udyapan'] = [['Pooja Without Material', 'Rs.4500'],
        ['Pooja With Material', 'Rs.4500']];

       // $service["Pooja"]['Munj/Thread Ceremony'] = [['Pooja Without Material', 'n/a'],['Pooja With Material','n/a']];


        $service["Pooja"]['Namkaran(Barse)'] = [['Pooja Without Material', 'Rs.1300'],['Pooja With Material','Rs.1300']];

        $service["Pooja"]['Navagraha Pooja'] = [['Pooja Without Material', 'Rs.4000'],['Pooja With Material','Rs.4000']];


     /*   $service["Pooja"]['Other'] = [['Pooja Without Material', 'Upto Rs.1000'],
        ['Pooja Without Material', 'Rs.1000-1500'],
        ['Pooja Without Material', 'Rs.1500-2000'],
        ['Pooja Without Material', 'Above Rs.2500']];*/


        $service["Pooja"]['Pitra Pooja'] = [['Pooja Without Material', 'Upto Rs.1000'],
        ['Pooja Without Material', 'Rs.1000-1500'],
        ['Pooja Without Material', 'Rs.1500-2000'],
        ['Pooja Without Material', 'Above Rs.2500'],
        ['Pooja With Material', 'Upto Rs.1000'],
        ['Pooja With Material', 'Rs.1000-1500'],
        ['Pooja With Material', 'Rs.1500-2000'],
        ['Pooja With Material', 'Above Rs.2500']];


        $service["Pooja"]['Pradosh Wrat Udyapan'] = [['Pooja Without Material', 'Rs.5000'],['Pooja With Material', 'Rs.5000']];


        $service["Pooja"]['Sakharpuda'] = [['Pooja Without Material', 'Rs.1750'],['Pooja With Material','1750']];


        $service["Pooja"]['Satyanarayan'] = [['Pooja Without Material', 'Rs.600'],['Pooja With Material', 'Rs.600']];

        $service["Pooja"]['Satyavinayak'] =[['Pooja Without Material', 'Rs.800'],
        ['Pooja With Material','Rs.800']];



        $service["Pooja"]['Shani Pooja'] = [['Pooja Without Material', 'Upto Rs.1000'],
        ['Pooja Without Material', 'Rs.1000-1500'],
        ['Pooja Without Material', 'Rs.1500-2000'],
        ['Pooja Without Material', 'Above Rs.2500'],
        ['Pooja With Material', 'Upto Rs.1000'],
        ['Pooja With Material', 'Rs.1000-1500'],
        ['Pooja With Material', 'Rs.1500-2000'],
        ['Pooja With Material', 'Above Rs.2500']];


        $service["Pooja"]['Sodmunj'] = [['Pooja Without Material', 'Rs.1650'],
        ['Pooja With Material', 'Rs.1650']];


        $service["Pooja"]['Sola Somwar Wrat Udyapan'] = [['Pooja Without Material', 'Rs.4500'],['Pooja With Material', 'Rs.4500']];


        $service["Pooja"]['Vastu Shanti'] = [['Pooja Without Material', 'Rs.8500'],['Pooja With Material', 'Rs.8500']];


        $service["Pooja"]['Wedding'] = [['Pooja Without Material', 'Rs.5500'],['Pooja With Material', 'Rs.5500']];

        $display["Pooja"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];

        //9 Carpenter
        $service["Carpenter"]['Furniture Repair'] = [['Furniture Assembly & Installation Door/Window Repair', 'Upon Inspection'],
        ['New Furniture Making', 'Upon Inspection'],
        ['Door & Latch Repairing', 'Upon Inspection'],
        ['General Work(Drilling etc.)', 'Upon Inspection'],
        ['Mirror Fixing', 'Upon Inspection']];

        $display["Carpenter"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];

        //10  Painting
        //prices yet not defined

        $service['Painting']["Interior Painting"] = [['', '']];


        $service['Painting']["Exterior Painting"]= [['', '']];


        $service['Painting']["Textured painting"] = [['', '']];


        $service['Painting']["Water Proofing"] = [['', '']];


        $display["Painting"] = [['attributes' => 'checkbox'], [ 'option'=> 'radio']];

        //11. Driver

         $service["Drivers on Demand"]["Drivers on Demand"]['Duration'] = [['Airport Transfer', '3Hrs, Rs.350'],
        ['Full Day', '8Hrs, Rs.850'],
        ['Half Day', '4Hrs, Rs.425']
        ];


        $service["Drivers on Demand"]["Drivers on Demand"]['Overnight'] = [['Yes', ''],
        ['No', '']];


        $service["Drivers on Demand"]["Drivers on Demand"]['Vehicle Type'] = [['HatchBack', ''],
        ['Sedan', ''],
        ['SUV', ''],
        ['MUV/MPV', ''],
        ['Convertible', ''],
        ['Van', ''],
        ['Luxury Cars', ''],
        ['Sports Cars', ''],
        ['Others', '']];

        $service["Drivers on Demand"]["Drivers on Demand"]['No.of Days'] = [['1', ''],
        ['2', ''],
        ['3', ''],
        ['4', ''],
        ['5+', '']];

        //12.interior
        $display["Drivers on Demand"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];

        $service["Interior Services"]["Interior Services"]['Type of Rooms'] = [['Living Room', ''],
        ['Bedrooms', ''],
        ['Kitchen', ''],
        ['Dining Area', ''],
        ['Other(Terrace,Utility Area etc.)', ''],
        ['All', '']];

         $service["Interior Services"]["Interior Services"]['Type of home'] = [['1 BHK', ''],
        ['2 BHK', ''],
        ['3 BHK', ''],
        ['4 BHK', ''],
        ['5 BHK', ''],
        ['Others', '']];

        $display["Interior Services"] = [['attributes' => 'radio'], [ 'option'=> 'radio']];

        $images = [
              'Appliance Repair' => 'mgs-appliance-repair-icon.png',
              'Beauty Services' => 'mgs-beauty.png',
              'Electrician' => 'mgs-electrician.png',
              'Pooja' => 'mgs-pooja-service.png',
              'Movers & Packers' => 'mgs-movers-and-packers.png',
              'Pest Control' => 'mgs-pest-control.png',
              'Plumbing' => 'mgs-plumbing.png',
              'Home Cleaning' => 'mgs-home-cleaning.png',
              'Carpenter' => 'mgs-carpenter.png',
              'Painting' => 'mgs-painting.png',
              'Drivers on Demand' => 'mgs-driver-on-demand.png',
              'Interior Services' => 'mgs-interior-decorator.png'
              ];


               $hero_images = [
              'Appliance Repair' => 'mgs-appliance-repair.jpg',
              'Beauty Services' => 'mgs-beauty-hero-image.jpg',
              'Electrician' => 'mgs-electrician-hero-image.jpg',
              'Pooja' => 'mgs-pooja-service-hero-image.jpg',
              'Movers & Packers' => 'mgs-home-movers&packers-hero-image.jpg',
              'Pest Control' => 'mgs-pest-control-hero-image.jpg',
              'Plumbing' => 'mgs-plumbing-hero-image.jpg',
              'Home Cleaning' => 'mgs-home-cleaning-hero-image.jpg',
              'Carpenter' => 'mgs-carpenter-hero-image.jpg',
              'Painting' => 'mgs-painting-hero-image.jpg',
              'Drivers on Demand' => 'mgs-driver-on-demand-hero-image.jpg',
              'Interior Services' => 'mgs-interior-hero-image.jpg'
              ];






         $files_options = ['disk' => 'mgspublic', 'folder'=> '/files/service'];

         $hero_files_options = ['disk' => 'mgspublic', 'folder'=> '/files/hero-images'];

        foreach ($service as $service => $products) {

          $friendly_url = preg_replace('/\s+/', '-', strtolower($service));
          $file_id = $this->file->createFile("./database/seeds/serviceimages/" . $images[$service], $files_options, true);
          $banner_id = $this->file->createFile("./database/seeds/serviceheroimages/" . $hero_images[$service],  $hero_files_options, true);


          $service_id = $this->service->createService(['service_name' => $service,
                                                 'weight' => 1,
                                                 'url' => "/service/$friendly_url",
                                                 'service_description' => "Lorem ipsum",
                                                 'is_active' =>1,
                                                 'file_id' => $file_id['id'],
                                                 'banner_id' => $banner_id['id']

                                                  ]);

          $products = array_filter($products);
          foreach ($products as  $product => $attributes) {
            $product_id = $this->product->createProduct(['product_name' => $product,
                                                 'product_description' => "Lorem ipsum",
                                                  'weight' => 1,
                                                 'is_active' =>1,
                                                  ]);
            $this->service->assignProductToService(['service_name' =>$service], ['product_name' => $product]);
            $attributes = array_filter($attributes);
            foreach ($attributes as  $key => $attribute ) {

              if(!empty($attribute[0]) && !is_array($attribute[0])){

                $price = isset($attribute[1]) ? $attribute[1] : '';
                $attribute_id  = $this->attribute->createAttribute(['attribute_name' => $attribute[0],
                                                     'weight' => 1,
                                                     'attribute_description' => "Lorem ipsum",
                                                     'is_active' => 1,
                                                     'price' => $price,
                                                     ]);
                $attribute_match = ['attribute_name' => $attribute[0],'price' => $price];

               $this->product->assignAttributeToProduct(['product_name' => $product], $attribute_match);
              } else if(!empty($attribute[0])){
                  $attribute_id  = $this->attribute->createAttribute(['attribute_name' => $key,
                                                     'weight' => 1,
                                                     'attribute_description' => "Lorem ipsum",
                                                     'is_active' => 1,
                                                     //'price' => isset($attribute[1]) ? $attribute[1] : '',
                                                     ]);
               $this->product->assignAttributeToProduct(['product_name' => $product], ['attribute_name' =>$key]);
               $attribute = array_filter($attribute);
               foreach ($attribute as $key1 => $option) {
                 $option_id  = $this->option_and_choice->createOption(['option_name' => $option[0],
                                                     'weight' => 1,
                                                     'option_description' => "Lorem ipsum",
                                                     'is_active' => 1,
                                                     'price' => isset($option[1]) ? $option[1] : '',
                                                     ]);
                 $this->attribute->assignOptionToAttribute(['attribute_name' => $attribute], ['option_name' => $option_id]);
               }
              }

            }
          }
        }
    }
}
