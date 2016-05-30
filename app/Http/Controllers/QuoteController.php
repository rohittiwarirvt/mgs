<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Event;
use Log;
use App\Http\Requests;
use App\Repositories\QuoteRepository;
use Larapi;
class QuoteController extends Controller
{


    public function __construct(QuoteRepository $quote)
    {
      $this->quote = $quote;
    }

   /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {

        $data = $request->all();
        return $this->quote->getAllQuotes($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
      $data = $request->only('user_information', 'quote_service_info', 'appointment_date','appointment_time', 'end_date','quote_source_id','user_id', 'special_instruction','quote_material_info','service_tax','vat','labour_charges','created_by','updated_by');
      $data = array_filter($data);

      return $this->quote->createQuote($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $quote = $this->quote->find($id);
        return $quote;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

      $data = $request->only('user_information', 'appointment_date', 'appointment_time', 'quote_price','status_id','service_info','quote_id','service_tax','vat','labour_charges', 'quote_material_info','created_by','updated_by');


      $data = array_filter($data);
      if ($data) {
        $options['quote_id'] = $id;
        if (isset($data['service_info'])) {
          $service_info = $data['service_info'];
          unset($data['service_info']);
          $shopping_cart_items =  json_decode($service_info);

           $this->quote->createQuoteServiceShoppingCartItem($shopping_cart_items, $options);

        }
        if (isset($data['quote_material_info'])) {
            $quote_material_info = $data['quote_material_info'];
            unset($data['quote_material_info']);
            $shopping_material_items =  json_decode($quote_material_info);
            $this->quote->createQuoteShoppingMaterialItem($shopping_material_items, $options);
        }
        $data['vat'] = isset($data['vat']) ? $data['vat'] : 0;
        $data['service_tax'] = isset($data['service_tax']) ? $data['service_tax'] : 0;
        $data['labour_charges'] = isset($data['labour_charges']) ? $data['labour_charges'] : 0;

       return Larapi::respondOk($this->quote->update($data, $id));
    }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function getQuoteServiceInfo(Request $request){
     $quote_id = $request->only('id');
      return $this->quote->getQuoteServiceInfo($quote_id);
    }

    public function getQuoteMaterialInfo(Request $request) {
      $quote_id = $request->only('id');
      return $this->quote->getQuoteShoppingMaterialInfo($quote_id);
    }

    public function getQuoteShoppingInfo(Request $request){
     $service_info = $request->only('service_info');

     return $this->quote->getQuoteShoppingInfo($service_info);
    }

    public function getQuoteStatus(Request $request){
      return $this->quote->getStatus();
   }

    public function assginCSR(Request $request){
        $data = $request->all();
      return $this->quote->assginQuoteCSR($data);
   }
}
