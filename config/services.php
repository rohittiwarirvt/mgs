<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

   'mailgun' => [
        'domain' => 'sandboxdcbd31642a4e4d56aab008ef68aa3bbf.mailgun.org',
        'secret' => 'key-2125444cebc3fa70184a0eeac49078c9',
    ],
    
    'ses' => [
        'key' => '',
        'secret' => '',
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => '',
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => '',
        'secret' => '',
    ],

];
