<?php

namespace App\Repositories;
use App\Models\Quote;
use App\Models\User;
use App\Models\Notes;
use App\Models\QuoteCSR;
use App\Models\OtpVerification;
use Carbon\Carbon;
use App\Models\ShoppingCartItem;
use DB;
use App\Models\ShoppingMaterial;
use App\Http\Requests;
use Event;
use Illuminate\Http\Request;
use App\Events\QuoteStatusChanged;
use Log;
use MGS\Services\Validation\QuoteValidator;
use MGS\Services\Validation\ShoppingValidator;
use MGS\Services\Validation\MaterialValidator;
use Larapi;
use Route;
use \Prettus\Validator\Exceptions\ValidatorException;
use \Prettus\Validator\Contracts\ValidatorInterface;
use Auth;

class QuoteRepository {

  private $user;
  private $quote;
  private $shopping_cart_item;
  protected $quote_validator, $material_validator, $shopping_validator;

  public function __construct(User $user, Quote $quote, ShoppingCartItem $shopping_cart_item, ProductRepository $product, ShoppingMaterial $shopping_material, QuoteValidator $quote_validator, MaterialValidator $material_validator, ShoppingValidator $shopping_validator,Request $request) {
    $this->user = $user;
    $this->quote = $quote;
    $this->shopping_cart_item = $shopping_cart_item;
    $this->product = $product;
    $this->shopping_material = $shopping_material;
    $this->quote_validator = $quote_validator;
    $this->material_validator = $material_validator;
    $this->shopping_validator = $shopping_validator;
    $this->request = $request;
  }

  public function getLastQuoteValue(){
    return $this->quote->max('id');
  }
  public function createQuote($data) {
    $data['status_id'] = 1;
    $data['expire_date'] = Carbon::now()->addDays(30);
    $data = array_filter($data);
    $quote_service_info = json_decode($data['quote_service_info']);
    $data['vat'] = isset($data['vat']) ? $data['vat'] : 0;
    $data['service_tax'] = isset($data['service_tax']) ? $data['service_tax'] : 0;
    $data['labour_charges'] = isset($data['labour_charges']) ? $data['labour_charges'] : 0;
    try {
      $validate_data = $this->quote_validator->with($data)->passesOrFail( ValidatorInterface::RULE_CREATE );
      $lastQuoteId = $this->getLastQuoteValue();
      if ($lastQuoteId == 0) {
        $lastQuoteId = 60000;
      }
      $data['id'] = $lastQuoteId + 25;
      $stored_data = $this->quote->create($data);
    }
     catch (ValidatorException $e) {
      return Larapi::respondBadRequest($e->getMessageBag(), 400);
     }

    $options['quote_id'] =  $stored_data['id'];
    $this->createQuoteServiceShoppingCartItem($quote_service_info, $options);

    if (isset($data['quote_material_info'])) {
            $quote_material_info = $data['quote_material_info'];
            unset($data['quote_material_info']);
            $shopping_material_items =  json_decode($quote_material_info);
            $this->createQuoteShoppingMaterialItem($shopping_material_items, $options);
      }
    $stored_data['quote_service_info'] = $quote_service_info;
    $stored_data['email_type'] = "quote-submit";

    $quote_data = $this->quote->findOrFail($stored_data['id']);
    if($quote_data && isset($data['special_instruction'])) {
        $quote_id  = $stored_data['id'];
        $new_note = array();
        $new_note['quote_id'] = $quote_id;
        $new_note['created_by'] = $data['user_id'];
        $new_note['user_id'] = $data['user_id'];
        $new_note['department_id'] = 1;  //static value for customer Dept
        $new_note['subject_id'] = 1;    //static value for customer subject
        $new_note['message'] = $data['special_instruction'];

        Notes::create($new_note);
        $success = array('succ_msg' => "Appointment details submitted Successfully.");
    }
    $userinfo = json_decode($quote_data['user_information']);
    $service  = $this->getQuoteService($stored_data['id']);

    //send email
    if (!empty($stored_data['id'])) {
      Event::fire(new QuoteStatusChanged($stored_data));
    }

    //send SMS
    $sms_data['type'] = $stored_data['email_type'];
    $sms_data['mobile'] = $userinfo->phonenumber;
    $sms_data['service_type'] = $service['service_name'];
    $sms_data['date'] = $stored_data['appointment_date'];
    $send_sms  = new OtpVerification;
    $send_sms->sendSMS($sms_data);

    return Larapi::respondOk($stored_data);
  }

  public function update(array  $data, $id, $attribute = 'id') {

    $quote = $this->quote->find($id);
    $message = isset($data['message']) ? $data['message'] : '';
    unset($data['message']);
    $quote_update = $this->quote->where($attribute, '=', $id)->update($data);
    $service  = $this->getQuoteService($id);
    $userinfo = json_decode($quote['user_information']);
    $sms_data = array();
    $sms_data['mobile'] = $userinfo->phonenumber;
    $sms_data['service_type'] = $service['service_name'];
    $sms_data['quote_id'] = $id;
    $sms_data['date'] = $quote['appointment_date'];
    $data['id'] = $quote->id;

    switch ($data['status_id']) {
      case '8':
          //Quote Publish
          $quote['email_type'] = $sms_data['type'] = "quote-publish";
          $quote['quote_service_info'] = $this->getQuoteServiceInfo($data);
          Event::fire(new QuoteStatusChanged($quote));
      break;

      case '5':
          //Quote reject by customer
          $quote['email_type'] = $sms_data['type'] = "quote-reject";
          $quote['quote_service_info'] = $this->getQuoteServiceInfo($data);
          $quote['message'] = $message;
          Event::fire(new QuoteStatusChanged($quote));
      break;

      case '11':
          //Quote Buy By Customer
          $quote['email_type'] = $sms_data['type'] = "quote-buy";
          $quote['quote_service_info'] = $this->getQuoteServiceInfo($data);

          $originalInput = $this->request->all();
          $sr_migration_request = Request::create('/api/old-app-sr-insertion?quote_id='.$id.'&source_id=1', 'GET');
          $this->request->replace($sr_migration_request->input());
          $response = Route::dispatch($sr_migration_request);
          $this->request->replace($originalInput);
          Event::fire(new QuoteStatusChanged($quote));
        break;

      default:
        # code...
        break;
    }

    //Send SMS
    if(!empty($sms_data['type'])) {
      $send_sms  = new OtpVerification;
      $send_sms->sendSMS($sms_data);
    }

    return $quote_update;
  }

  public function find($id, $columns = array('*')) {
    $quote = $this->quote->find($id, $columns);
    $status = $this->getStatus($quote['status_id']);
    $quote['status'] = json_encode(array('internal'=>$status[0]->status_internal_name, 'external'=>$status[0]->status_external_name));
    $quote['user'] = $quote->user;
    return Larapi::respondOk($quote);
  }

  public function findQuoteBy($matches, $columns = array('*')) {
    $quote = $this->quote->where($matches)->first($columns);
    return Larapi::respondOk($quote);
  }

  public function assginQuoteCSR($data) {
     foreach($data['quotes'] as $quote_id) {
       $data['quote_id'] = $quote_id;
       QuoteCSR::create($data);
     }

    return Larapi::respondOk($data);
  }

  public function getAllQuotes(array $data) {
      $quotes = Quote::select('quotes.*');

    // Filter Result Based on Criteria
    if( isset( $data["filters"] ) ){
      $filters = json_decode($data["filters"]);
      $quotes = $this->applyFilters($quotes, $filters);
    }
    $quotes = $quotes->orderBy('id', 'desc')->get();
    foreach($quotes as $key=>$quote) {
        $note_obj = new Notes;
        $quotes[$key]->notes = $note_obj->getLastNotes($quote->id);
        $status = $this->getStatus($quote->status_id);
        $quotes[$key]->status = json_encode(array('internal'=>$status[0]->status_internal_name, 'external'=>$status[0]->status_external_name));
        $data['id'] = $quote->id;
        $product_info  = $this->getQuoteServiceInfo($data);
        $service_details = $this->getQuoteShoppingInfo(array ('service_info' => json_encode($product_info)));

        $productid = !empty($product_info[0]) ? $product_info[0]->product_id : 0;
        if(!empty($product_info[0])) {
           $productid  = $product_info[0]->product_id;
           $service_info = $this->product->find($productid)->services;
           $quotes[$key]->service_name = !empty($service_info[0]) ? $service_info[0]->service_name : '';
        }
        $quotes[$key]->csr = $this->getQuoteCSR($quote->id);
      }

     return $quotes;
  }

  public function getQuoteCSR($quote_id) {
    $csr = QuoteCSR::select('users.first_name');
    $csr = $csr->join('users', 'users.id', '=', 'quote_csr.user_id')
              ->where('quote_csr.quote_id', $quote_id)
              ->orderBy('quote_csr.id', 'desc')
              ->first();
    return $csr;
  }

  public function applyFilters($quotes, $filters){

    //User based Services
    if(!empty($filters->user_id)) {
      $quotes = $quotes->where('user_id', $filters->user_id);
    }
    //Quick search
    if( !empty($filters->search_by) && !empty($filters->search_for)) {
        $key  = $filters->search_by;
        $value = $filters->search_for;
        if($key == 'quote_id') {
            $quotes = $quotes->where('id', $value);
        }
        else{
           $str =  '"'. $key.'":"([^"]*)'.$value. '([^"]*)"';
           $quotes = $quotes->where('user_information', 'REGEXP', $str);
        }
    }
    else{
        //Advanced Search
        if(!empty($filters->service_type)){
          $quotes  = $quotes->join('shopping_cart_items', 'quotes.id', '=', 'shopping_cart_items.quote_id')
                            ->join('service_product', 'service_product.product_id', '=', 'shopping_cart_items.product_id')
                            ->where('service_product.service_id', $filters->service_type)
                            ->groupBy('quotes.id');
        }
        if(!empty($filters->search_date)) {
          $fromdate = Carbon::parse($filters->fromDate);
          $fromdate = $fromdate->format('Y-m-d');
          $todate = Carbon::parse($filters->toDate);
          $todate = $todate->format('Y-m-d');
          $quotes = $quotes->whereBetween($filters->search_date, array($fromdate, $todate));
        }
        if(!empty($filters->status_id)){
            $quotes = $quotes->where('status_id', $filters->status_id);
        }
        if(!empty($filters->note_status)){
             $quotes = $quotes->join('notes', 'notes.quote_id', '=', 'quotes.id')
                              ->where('notes.status', $filters->note_status)
                              ->groupBy('quotes.id')
                              ->orderBy('notes.id', 'desc');
        }
    }
    return $quotes;
  }

  public  function createQuoteServiceShoppingCartItem($data, $options = NULL) {

      if(!empty($data)){
        $stored_shopping = json_decode(json_encode($this->getQuoteServiceInfo(['id' => $options['quote_id']])), true);
        foreach ($data as $key => $service) {
          $service = json_decode(json_encode($service), true);
          $shopping_cart_item['quote_id'] = $options['quote_id'];
          $shopping_cart_item['product_id'] = $service['product']['id'];

          if (isset($service['option']) && !empty($service['option']) ) {
            $shopping_cart_item['attribute_id'] = $service['attribute']['id'];
            $shopping_cart_item['option_id'] = $service['option']['id'];
            $shopping_cart_item['option_value'] = isset($service['option']['price']) ? (int)$service['option']['price'] : 0;
            if (isset($service['option']['quantity'])) {
              $shopping_cart_item['quantity'] = (int)$service['option']['quantity'];
              $shopping_cart_item['unit'] = isset($service['option']['unit']) ? $service['option']['unit'] :"";
            }
            $shopping_cart_item['discount'] = !empty($service['option']['setDiscount']) && !empty($service['option']['discount']) ? (int)$service['option']['discount']: "";

          }
          else if(empty($service['attribute']) && $service['product']) {
            $shopping_cart_item['product_value'] = isset($service['product']['price']) ? (int)$service['product']['price'] : 0;
            if (isset($service['product']['quantity'])) {
              $shopping_cart_item['quantity'] = (int)$service['product']['quantity'];
              $shopping_cart_item['unit'] = isset($service['product']['unit']) ? $service['product']['unit'] :"";
            }
            $shopping_cart_item['discount'] = !empty($service['product']['setDiscount']) && !empty($service['product']['discount']) ? (int)$service['product']['discount']: "";
          }
          else if(isset($service['attribute']) && !empty($service['attribute'])) {
            $shopping_cart_item['attribute_id'] = $service['attribute']['id'];
            $shopping_cart_item['attribute_value'] =  isset($service['attribute']['price']) ? (int)$service['attribute']['price'] : 0;
            if (isset($service['attribute']['quantity'])) {
              $shopping_cart_item['quantity'] = (int)$service['attribute']['quantity'];
              $shopping_cart_item['unit'] = isset($service['attribute']['unit']) ? $service['attribute']['unit'] :"";
            }

            $shopping_cart_item['discount'] = !empty($service['attribute']['setDiscount']) && !empty($service['attribute']['discount']) ? (int)$service['attribute']['discount']: "";
          }


            try {
            $whereToClause = array_only($shopping_cart_item, ['quote_id','product_id', 'attribute_id', 'option_id']);

            $selection_present = json_decode(json_encode($this->findShoppingCartBy($whereToClause)), true);
            $validate_data = $this->shopping_validator->with($shopping_cart_item)->passesOrFail();


            if (!empty($selection_present)) {
              $shopping_cart_item['id'] = $selection_present['id'];
              $is_exist_id = $this->searchForId($shopping_cart_item['id'], $stored_shopping);
              if (!is_null($is_exist_id)) {
                $stored_shopping[$is_exist_id]['no_delete'] = 1 ;
              }
             $this->updateShoppingCart($shopping_cart_item, $shopping_cart_item['id']);
            }
            else {
              $this->createShoppingCartItem($shopping_cart_item);
            }
          }
          catch (ValidatorException $e) {
            return Larapi::respondBadRequest($e->getMessageBag(), 400);
          }

        }
        $this->deleteShopRecords($stored_shopping);

      }

}

  public function searchForId($id, $array) {
    foreach ($array as $key => $val) {
      if (isset($val['id']) && $val['id'] === $id) {
           return $key;
       }
   }
   return null;
  }

  public function deleteShopRecords($array) {
   foreach ($array as $key => $val) {
       if (!(isset($val['no_delete']) && $val['no_delete'] === 1)) {
            $matches['id'] = $val['id'];
           $this->deleteShoppingCart($matches);
       }
   }
   return null;
  }

  public function deleteShoppingCart($matches)
   {
     return $this->shopping_cart_item->where($matches)->delete();
  }


  public function deleteMaterials($matches)
   {
     return $this->shopping_material->where($matches)->delete();
  }

  public function deleteMaterialRecords($array) {
   foreach ($array as $key => $val) {
       if (!(isset($val['no_delete']) && $val['no_delete'] === 1)) {
            $matches['id'] = $val['id'];
           $this->deleteMaterials($matches);
       }
   }
   return null;
  }

 public function updateShoppingCart(array  $data, $id, $attribute = 'id') {
   $shopping_cart_item = $this->shopping_cart_item->where($attribute, '=', $id)->update($data);
   return Larapi::respondOk($shopping_cart_item);
  }

  public  function createShoppingCartItem($data) {
    $shopping_cart_item = $this->shopping_cart_item->firstOrCreate($data);
    return Larapi::respondOk($shopping_cart_item);
  }

  public function getQuoteServiceInfo($data){
    return $this->quote->find($data['id'])->quotes_services;
  }

  public function getQuoteShoppingInfo($data){
     $result = json_decode($data['service_info']);
     $service_info_collection = [];
     foreach ($result as $key => $cart) {
          $cart = (array)$cart;
          $service_info['cart_id'] = $cart['id'];
          $service_info['service'] =  $this->shopping_cart_item->find($cart['id'])->product->services;
          $service_info['product'] =  $this->shopping_cart_item->find($cart['id'])->product;
          $service_info['attribute'] = $this->shopping_cart_item->find($cart['id'])->attribute;
          $service_info['option'] = $this->shopping_cart_item->find($cart['id'])->option;
          $service_info['product_value'] = $cart['product_value'];
          $service_info['attribute_value'] = $cart['attribute_value'];
          $service_info['option_value'] = $cart['option_value'];
          $service_info['quantity'] = $cart['quantity'];
          $service_info['unit'] = $cart['unit'];
          $service_info['discount'] = $cart['discount'];
          $service_info_collection[] = $service_info;
     }

     return $service_info_collection;
  }

  public function getStatus($id='')
  {
    $status = DB::table('status');
    if($id)
        $status = $status->where('id', $id);
    $status = $status->select('id', 'status_internal_name', 'status_external_name' )->where('is_active',1)->get();
    return $status;
  }

  public function getQuoteService($id) {
    $service_info['service'] =  $this->findShoppingCartBy(['quote_id' => $id])->product->services;
    return $service_info['service'][0];
  }

  public function findShoppingCartBy($matches, $columns = array('*')) {
    return $this->shopping_cart_item->where($matches)->first($columns);
  }

   public function updateShoppingMaterials(array  $data, $id, $attribute = 'id') {
    $shopping_cart_material = $this->shopping_material->where($attribute, '=', $id)->update($data);
    return Larapi::respondOk($shopping_cart_material);
  }

  public  function createShoppingMaterial($data) {
    $shopping_cart_material =  $this->shopping_material->firstOrCreate($data);
    return Larapi::respondOk($shopping_cart_material);
  }

  public function getQuoteShoppingMaterialInfo($data){
    $material_info = $this->quote->find($data['id'])->quotes_materials;
    return Larapi::respondOk($material_info);
  }

  public  function createQuoteShoppingMaterialItem($materials, $options = NULL) {
    $stored_material = json_decode(json_encode($this->quote->find($options['quote_id'])->quotes_materials), true);
    if (!empty($materials)) {

      foreach ($materials as $key => $material) {
        $material = json_decode(json_encode($material), true);

       $material = array_only($material, ['unit_price', 'material_name','material_quantity','material_total','id']);
       $material['quote_id'] = $options['quote_id'];
        $material = array_filter($material);
       try {
        $validate_data = $this->material_validator->with($material)->passesOrFail();
        if (!isset($material['id'])) {
          $selection_present = json_decode(json_encode($this->findShoppingMaterialBy($material)), true);

          $material['id'] = $selection_present['id'];
        }

        if (!empty($material['id'])) {
          $shopping = $this->updateShoppingMaterials($material, $material['id']);
          $is_exist_id = $this->searchForId($material['id'], $stored_material);
          if (!is_null($is_exist_id)) {
                $stored_material[$is_exist_id]['no_delete'] = 1 ;
          }
        } else {
          $shopping = $this->createShoppingMaterial($material);
        }
       }
     catch (ValidatorException $e) {
      return Larapi::respondBadRequest($e->getMessageBag(), 400);
     }


      }

    $this->deleteMaterialRecords($stored_material);
    } else if ( !empty($stored_material) ) {
        $this->deleteMaterials($options);
      }
  }

  public function findShoppingMaterialBy($matches, $columns = array('*')) {
    return $this->shopping_material->where($matches)->first($columns);

  }
}



