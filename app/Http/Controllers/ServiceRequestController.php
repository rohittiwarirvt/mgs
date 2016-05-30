<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Repositories\ServiceRequestRepository;
use App\Repositories\QuoteRepository;

class ServiceRequestController extends Controller
{

	public function __construct(ServiceRequestRepository $ServiceRequestRepository,QuoteRepository $QuoteRepository) {
      $this->ServiceRequestRepository = $ServiceRequestRepository;
      $this->QuoteRepository = $QuoteRepository;
    }

	public function convertQuoteToSR(Request $request){
    	$quoteid = $request->input('quote_id');
    	if($quoteid){
    		$quotedetails = $this->QuoteRepository->find($quoteid);
    		$result = $this->ServiceRequestRepository->convertQuoteToSR($quotedetails);
    		if($result == "success")
    			return response()->json(['status'=>'success', 'message'=>'SR Created']);
            else
                return response()->json(['status'=>'error', 'message'=>$result], 403);
        }
    	else{
    		return response()->json(['status'=>'error', 'message'=>'Quote Id is Not Present'], 403);
        }
    }   

    public function insertOldSR(Request $request){
        $quoteid = $request->input('quote_id');
        $source = $request->input('source_id');
        if($quoteid && $source){
            $quotedetails = $this->QuoteRepository->find($quoteid);
            $quoteData = $quotedetails->getData();
            $serviceinfos = $this->QuoteRepository->getQuoteServiceInfo(array("id"=>$quoteData->response->id));
            $result = $this->ServiceRequestRepository->insertOldSR($quotedetails,$serviceinfos);
            return response()->json(['status'=>'success','Service_Request_Id'=>$result['Service_Request_Id'],
                                                         'Customer_Service_Request_Relation_Id'=>$result['Customer_SR_ID']
                                                                    ]);
        }
        else{
            return response()->json(['status'=>'error', 'message'=>'Either Quote ID or Source ID is not present'], 403);   
        }
    }
}
