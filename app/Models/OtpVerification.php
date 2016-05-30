<?php

namespace App\Models;

use GuzzleHttp;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Shorten;

class OtpVerification extends Model
{
    protected $table = 'message_directory';
    protected $fillable = [
        'sender', 'receiver', 'sms_type', 'sms_body'  ];


    //Send SMS
    public function sendSMS($data){
    	$type = $data['type'];
    	$to = $data['mobile'];

      $replacements = [ '(servicename)' => $data['service_type'],
                      ];

      $date = Carbon::parse($data['date']);
      $timezone = "UTC";
      $date = new \DateTime($date, new \DateTimeZone($timezone));
      $date->setTimezone(new \DateTimeZone('Asia/Calcutta'));
      $buydate = $date->format('Y-m-d h:i:s a');

      /* Note date & Time */
      $notedate = Carbon::now();
      $timezone = "UTC";
      $notedate = new \DateTime($notedate, new \DateTimeZone($timezone));
      $notedate->setTimezone(new \DateTimeZone('Asia/Calcutta'));
      $notedate = $notedate->format('Y-m-d h:i:s a');

    	switch ($type) {

          case 'quote-submit':
           $message = 'MyGharSeva- Thank you for choosing our (servicename) service. Our expert will call you back shortly.';
          break;

          case 'quote-publish':
          $quotelink = url('/') . "/#/myservices/quoteview/" . $data['quote_id'];
          $link = Shorten::url($quotelink);
           $message = 'MyGharSeva- Quotation has been submitted for your (servicename) service. Click here (link) to view details.';
           $replacements  +=[  '(link)' => $link
                              ];
          break;

          case 'quote-reject':
          $quotelink = url('/') . "/#/myservices/quoteview/" . $data['quote_id'];
          $link = Shorten::url($quotelink);
           $message = 'MyGharSeva- This is to inform that you have declined quotation for (servicename) service. Click here (link) to view details.';
           $replacements  +=[  '(link)' => $link
                              ];
          break;

          case 'quote-buy':
           $message = 'MyGharSeva- Your (servicename) service #(ServiceRequestID) has been booked as per your request on (date_time).';
            $replacements +=[ '(ServiceRequestID)' => $data['quote_id'],
                               '(date_time)' => $data['date']
                              ];
          break;

          case 'note-post':
            $message  = 'MyGharSeva- You have posted a note for (servicename) service on (date).';
            $replacements +=[ '(date)' => $notedate
                              ];
          break;

          case 'note-reply':
          $quotelink = url('/') . "/#/myservices/quoteview/" . $data['quote_id'];
          $link = Shorten::url($quotelink);
            $message = 'MyGharSeva- We have replied to your note posted for (servicename) service on (date). Click here (link) to read the response.';
            $replacements  +=[ '(date)' => $notedate,
                                '(link)' => $link
                              ];
          break;

          default:
          	# code...
          break;
      	}
      	$tokens = array_keys($replacements);
	    $values = array_values($replacements);
	    $message =  str_replace($tokens, $values, $message);

	    $api_key = 'Ad151d8cc6a44c12831634253de7c7aa8';
	    $request_url = 'http://api-alerts.solutionsinfini.com/v3/?method=sms';

	    $client = new GuzzleHttp\Client();
	    $api_url = $request_url .'&api_key='.$api_key.'&to='.$to.'&sender=MGSSEV&message='.$message.'&format=json&custom=1,2&flash=0';
	    $send_sms = $client->request('GET', $api_url);
	    $send_sms->getStatusCode();
	    $send_sms->getHeader('content-type');
	    json_encode($send_sms->getBody());

	    /* save sms data*/
	    $otpdata['receiver'] = $to;
	    $otpdata['sender']   = 'MGSSEV';
	    $otpdata['sms_type'] = $type;
	    $otpdata['sms_body'] = $message;
	    $otpdata['status']   = 1;
	    $this->create($otpdata);
    }
}
