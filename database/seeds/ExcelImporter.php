<?php

use Illuminate\Database\Seeder;
use Excel as Excel;
use App\Repositories\ServiceRepository;
use App\Repositories\ProductRepository;
use App\Repositories\OptionAndOptionChoiceRepository;
use App\Repositories\AttributeRepository;
use Illuminate\Support\Facades\File;
use App\Models\FileEntry;
use App\Repositories\FileRepository;

class ExcelImporter extends Seeder
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
      DB::table('services')->truncate();
      DB::table('products')->truncate();
      DB::table('attributes')->truncate();
      DB::table('options')->truncate();
      DB::table('service_product')->truncate();
      DB::table('product_attribute')->truncate();

        $file_path = './database/seeds/service-import.xls';
        Excel::load($file_path, function($reader) {
          $this->importer($reader);
        });


    }

    public function importer($reader) {

      $excel = $reader;
      $excel->each(function($sheet) {
          $sheet_name  =$sheet->getTitle();


          switch ($sheet_name) {
            case 'services':
                $this->createServices($sheet);
              break;
            case 'products';
                $this->createProducts($sheet);
              break;
            case 'attributes';
                $this->createAttributes($sheet);
              break;
            case 'options';
                $this->createOptions($sheet);
              break;
            default:
              # code...
              break;
          }

       });
    }

    public function createServices($sheet) {
        $images = [

              'Beauty Services' => 'mgs-beauty.png',
              'Painting' => 'mgs-painting.png',
              'Pest Control' => 'mgs-pest-control.png',
              'Home Cleaning' => 'mgs-home-cleaning.png',
              'Appliance Repair' => 'mgs-appliance-repair-icon.png',
              'Plumber' => 'mgs-plumbing.png',
              'Electrician' => 'mgs-electrician.png',
              'Carpenter' => 'mgs-carpenter.png',
              'Interior Designer' => 'mgs-interior-decorator.png',
              'Pooja Services' => 'mgs-pooja-service.png',
              'Driver on Demand' => 'mgs-driver-on-demand.png',
              'Movers & Packers' => 'mgs-movers-and-packers.png'

              ];


               $hero_images = [

              'Beauty Services' => 'mgs-beauty-hero-image.jpg',
              'Painting' => 'mgs-painting-hero-image.jpg',
              'Pest Control' => 'mgs-pest-control-hero-image.jpg',
              'Home Cleaning' => 'mgs-home-cleaning-hero-image.jpg',
              'Appliance Repair' => 'mgs-appliance-repair.jpg',
              'Plumber' => 'mgs-plumbing-hero-image.jpg',
              'Electrician' => 'mgs-electrician-hero-image.jpg',
              'Carpenter' => 'mgs-carpenter-hero-image.jpg',
              'Interior Designer' => 'mgs-interior-hero-image.jpg',
              'Pooja Services' => 'mgs-pooja-service-hero-image.jpg',
              'Driver on Demand' => 'mgs-driver-on-demand-hero-image.jpg',
              'Movers & Packers' => 'mgs-home-movers&packers-hero-image.jpg'
              ];

         $files_options = ['disk' => 'mgspublic', 'folder'=> '/files/service'];

         $hero_files_options = ['disk' => 'mgspublic', 'folder'=> '/files/hero-images'];
      foreach ($sheet as $key => $row) {
        $service = $row->toArray();
        if (($service['service_name'])) {
          $file_id = $this->file->createFile("./database/seeds/serviceimages/" . $images[$service['service_name']], $files_options, true);
          $banner_id = $this->file->createFile("./database/seeds/serviceheroimages/" . $hero_images[$service['service_name']],  $hero_files_options, true);
          $service['file_id'] = $file_id['id'];
          $service['banner_id'] = $banner_id['id'];
        $this->service->createService($service);
        }

      }


    }

    public function createProducts($sheet) {
      foreach ($sheet as $key => $row) {
        $product = $row->toArray();
        if(($product['product_name'])){
          $service['service_name'] = array_pull($product, 'service_name');
          $this->product->createProduct($product);
          $this->service->assignProductToService( $service, ['product_name' => $product['product_name']]);
        }

      }
    }

    public function createAttributes($sheet) {
      foreach ($sheet as $key => $row) {
        $attribute = $row->toArray();
        if(($attribute['attribute_name'])){
          $product['product_name'] = array_pull($attribute, 'product_name');
          $this->attribute->createAttribute($attribute);
          $this->product->assignAttributeToProduct($product, $attribute);
        }

      }
    }

    public function createOptions($sheet){
      foreach ($sheet as $key => $row) {
        $option = $row->toArray();
        if(($option['option_name'])){
          $attribute['attribute_name'] = array_pull($option, 'attribute_name');
          $this->option_and_choice->createOption($option);
          $this->attribute->assignOptionToAttribute($attribute, $option);
        }

      }
    }

}
