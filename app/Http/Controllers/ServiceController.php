<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Service;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\FileEntry;
use App\Http\Requests;
use App\Repositories\ServiceRepository;
use App\Repositories\FileRepository;
use Storage;

class ServiceController extends Controller
{
  protected $service;

  public function __construct(ServiceRepository $service)
    {
      $this->service = $service;
    }

  public function index()
    {
      return $this->service->getAllServices();
    }

  public function store(Request $request)
    {

     return $this->service->createService($request->only('service_name', 'service_description', 'weight', 'is_active','url'));
    }

  public function update(Request $request, $id)
    {

    }

  public function destroy($id)
    {

    }


  public function show($id)
    {
        $service = $this->service->find($id);
        return $service;
    }

  public function getService(Request $request) {
    $search = $request->only('service_name', 'url');
    $service = array_filter($search);
    return response()->json($this->service->findServiceBy($service));
  }

  public function assignProductToService(Request $request){
      $service_name = $request->only('service_name');
      $product_name = $request->only('product_name');
      return $this->service->assignProductToService($service_name, $product_name);
    }

  public function showServiceWithProduct(Request $request){
      $service = $request->only('service_name','url');
      return $this->service->showServiceWithProduct($service);
    }

  public function showFaq(Request $request){
     $service_url = $request->only('url');
     $service_details = DB::table('services')->where('url', $service_url)->get();
     foreach($service_details as $service){
         $service_id =  $service->id;
     }
     $faq_details = DB::table('faq')->where('service_id', $service_id)->select('question', 'answer')->get();
     return json_encode($faq_details);

     }

     public function showFeature(Request $request){
      $service_url = $request->only('url');
      $service_details = DB::table('services')->where('url', $service_url)->get();
      foreach($service_details as $service){
         $service_id =  $service->id;
     }
     
     $feature_details = DB::table('features')->where('service_id', $service_id)
                        ->join('files', 'features.file_id', '=', 'files.id')
                        ->select('features.title', 'features.description', 'files.file_uri')
                        ->get();

      if(empty($feature_details)){
        $feature_details = DB::table('features')->where('service_id', $service_id)
                        ->select('title', 'description')->get();
                    
      }

      return $feature_details;
  }

  public function showHeroImages(Request $request){

      $service_url = $request->only('url');
      $service_details = DB::table('services')->where('url', $service_url)->get();
      foreach($service_details as $service){
         $service_name =  $service->service_name;
     }

     $image_details = DB::table('services')->where('service_name', $service_name) 
                      ->join('files', 'services.banner_id', '=', 'files.id')
                      ->select('files.file_uri', 'services.tag_line')
                      ->get();

     return $image_details;
  
    }
}
 
