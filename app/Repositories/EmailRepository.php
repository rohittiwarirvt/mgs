<?php

namespace App\Repositories;

use App\Models\EmailTemplate;
use Carbon\Carbon;
use App\Models\TemplateQueue;
use App\Repositories\UserRepository;
use Mail;
use Log;
use URL;

class EmailRepository {

  private $email_template;
  private $template_queue;
  private $user;

  public function __construct(EmailTemplate $email_template, TemplateQueue $template_queue, UserRepository $user) {

    $this->email_template = $email_template;
    $this->template_queue = $template_queue;
    $this->user = $user;
  }


public function sendMail($data) {
  $base_url = URL::to('/');
  $user = $this->user->find($data['user_id']);
  $template = $this->findEmailTemplateBy(['type'=> $data['type']]);

  $login_registration = "<a href=$base_url'>Click here</a>";
  $contextual_text = 'Login';
  if ($user['user_type'] == 'guest') {
    $contextual_text = 'Register';
  }
      //var_dump($data);
      $replacements = [ '{{firstname}}' => $user['first_name'],
                        '{{username}}' => $user['first_name']
                      ];

      switch ($data['type']) {
        case 'quote-publish':
        $replacements +=[ '{{servicename}}' => $data['service_name'],
                           '{{firstname}}'=> $user['first_name'],
                           '{{enquiryDate}}' => $data['quote_creation_date'],
                           '{{quotes}}' => $base_url. '#/quoteview/' . $data['quote_id']
                           ];
        break;

        case 'quote-submit' :
        $subservices = implode(",", $data['subservices']);
        $subservice_details = implode("<br />", $data['subservices']);
        $replacements +=[ '{{servicename}}' => $data['service_name'],
                           '{{customerName}}'=> $user['first_name'],
                           '{{customerPhone}}' => $user['phonenumber'],
                            '{{customerEmail}}'=> $user['email'],
                            '{{date_time}}' => $data['appointment_date']
                           ];
        break;

        case 'quote-reject':
        $replacements +=[ '{{servicename}}' => $data['service_name'],
                          '{{quotesubmitdate}}'=> $data['appointment_date'],
                          '{{notes}}' => $data['message']
                         ];
        break;

        case 'quote-buy':
        $replacements +=[ '{{servicename}}' => $data['service_name'],
                          '{{ServiceRequestID}}' => $data['quote_id'],
                          '{{date_time}}'=> $data['appointment_date']
                         ];
        break;

        case 'note-post-admin':
        case 'note-post':
        case 'note-reply':
            $notedate = Carbon::now();
            $timezone = "UTC";
            $date = new \DateTime($notedate, new \DateTimeZone($timezone));
            $date->setTimezone(new \DateTimeZone('Asia/Calcutta'));
            $date = $date->format('Y-m-d h:i:s a');
            $replacements +=[ '{{date}}' => $date,
                              '{{Notes}}' => $data['message'],
                              '{{ServiceRequestID}}' => $data['quote_id']
                            ];
        break;

        default:
          # code...
          break;
      }
      $template_subject = $this->token_replace($replacements, $template->subject);
      $template_body =  $this->token_replace($replacements, $template->template_body);
      $template_id = $template->id;

  $mail_data =  array (
    'user_email' => $user['email'],
    'email_body' => $template_body,
    'email_subject' => $template_subject,
    'email_from' => 'support@mygharseva.com',
    );
  Mail::send('auth.mails', $mail_data, function($message) use($mail_data){
    $message->subject($mail_data['email_subject'], 'Subject');
    $message->to($mail_data['user_email'], 'user');
    $message->from($mail_data['email_from'], 'MygharSeva');
  });

  $mail_queue = [
    'user_id' =>  $user['id'],
    'template_id' => $template_id,
    'to' => $user['email'],
    'from' => $mail_data['email_from'],
    'template_data' => $template_body
    ];

  $template_queue =$this->createTemplateQueue($mail_queue);
  }




  public function findEmailTemplate($id, $columns = array('*')) {
    return $this->email_template->find($id, $columns);
  }

  public function findEmailTemplateBy($matches, $columns = array('*')) {
    return $this->email_template->where($matches)->first($columns);
  }


  public function findTemplateQueue($id, $columns = array('*')) {
    return $this->template_queue->find($id, $columns);
  }

  public function findTemplateQueueBy($matches, $columns = array('*')) {
    return $this->template_queue->where($matches)->first($columns);
  }

  public function createTemplateQueue($data) {
    return $this->template_queue  ->firstOrCreate($data);
  }

  public function token_replace($replacements, $text){
    $tokens = array_keys($replacements);
    $values = array_values($replacements);
    return str_replace($tokens, $values, $text);
  }


}
