<?php

/*
 * Configurations for MTN Momo Account.
 */

return [
  

    // Momo API transaction currency code.
    'currency'          => env('MTN_MOMO_CURRENCY', 'EUR'),
  

    'environment'       => env('MTN_MOMO_ENVIRONMENT', 'sandbox'),
 

    'base_url'          => env('MTN_MOMO_API_BASE_URI', 'https://sandbox.momodeveloper.mtn.com/'),
      

    'collection' => [

        'api_secret'    => env('MTN_MOMO_COLLECTION_API_SECRET'),
        
        'primary_key'   => env('MTN_MOMO_COLLECTION_PRIMARY_KEY'),

        'user_id'       => env('MTN_MOMO_COLLECTION_USER_ID'),

        'callback_url'  => env('MTN_MOMO_COLLECTION_CALLBACK_URL')
    ],
 

    'disbursement' => [

        'primary_key'   => env('MTN_MOMO_DISBURSEMENT_PRIMARY_KEY'),

        'api_secret'    => env('MTN_MOMO_DISBURSEMENT_API_SECRET'),

        'user_id'       => env('MTN_MOMO_DISBURSEMENT_USER_ID'),

        'callback_url'  => env('MTN_MOMO_DISBURSEMENT_CALLBACK_URL')
    ],
 

    'remittance' => [

        'primary_key'   => env('MTN_MOMO_REMITTANCE_PRIMARY_KEY'),

        'api_secret'    => env('MTN_MOMO_REMITTANCE_API_SECRET'),

        'user_id'       => env('MTN_MOMO_REMITTANCE_USER_ID'),

        'callback_url'  => env('MTN_MOMO_REMITTANCE_CALLBACK_URL')
    ],
];
