<?php


return [

    /*
    |--------------------------------------------------------------------------
    | Messenger Default User Model
    |--------------------------------------------------------------------------
    |
    | This option defines the default User model.
    |
    */

    'user' => [
        'model' => 'App\User'
    ],

    /*
    |--------------------------------------------------------------------------
    | Messenger Pusher Keys
    |--------------------------------------------------------------------------
    |
    | This option defines pusher keys.
    |
    */

    'pusher' => [
        'app_id'     => env('PUSHER_APP_ID'),
        'app_key'    => env('PUSHER_APP_KEY'),
        'app_secret' => env('PUSHER_APP_SECRET'),
        'options' => [
            'cluster'   => env('PUSHER_APP_CLUSTER'),
            'encrypted' => true
        ]
    ],

    /**
     *|
     */
    "messengerpath"      => "{lang}/x/messenger",
    /**
     *|
     */
    "master_file_extend" => "layouts.backend",
];
