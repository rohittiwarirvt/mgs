<?php

namespace App\Listeners;

use App\Repositories\QuoteRepository;
use App\Repositories\EmailRepository;
use App\Events\QuoteStatusChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;
use Carbon\Carbon;

class QuoteStatusMail
{
    protected $email_repository;
    public $data;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(EmailRepository $email_repository, QuoteRepository $quote)
    {
        $this->email_repository = $email_repository;
        $this->quote = $quote;
    }

    /**
     * Handle the event.
     *
     * @param  QuteStatusChanged  $event
     * @return void
     */
    public function handle(QuoteStatusChanged $event)
    {

        $quote = $event->quote;
        $service = json_decode($this->quote->getQuoteService($quote['id']));
        $subservice = array();
        foreach($quote['quote_service_info'] as $quotes){
            array_push($subservice, $quotes->product->product_name);
        }
        $appointment_date = Carbon::parse($quote['appointment_date']);
        $timezone = "UTC";
        $date = new \DateTime($appointment_date, new \DateTimeZone($timezone));
        $date->setTimezone(new \DateTimeZone('Asia/Calcutta'));
        $quote['appointment_date'] = $date->format('Y-m-d h:i:s a');


        $data = [
             'user_id' => $quote['user_id'],
             'type' => $quote['email_type'],
             'quote_id' => $quote['id'],
             'quote_creation_date' => $quote['appointment_date'],
             'appointment_date' => $quote['appointment_date'],
             'appointment_time' => $quote['appointment_time'],
             'message' => $quote['message'],
             'service_name' => $service->service_name,
             'subservices'  => $subservice,

        ];

        $this->email_repository->sendMail($data);
    }
}
