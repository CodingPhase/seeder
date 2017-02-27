<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Amount of Seeds
    |--------------------------------------------------------------------------
    |
    | Amount of seeds that are seeded as default.
    |
    */

    'default_amount' => 50,

    /*
    |--------------------------------------------------------------------------
    | Progress Bar Style
    |--------------------------------------------------------------------------
    |
    | Default Style of Progress Bar.
    |
    */

    'compact' => true,

    /*
    |--------------------------------------------------------------------------
    | Default Amount of Seeds for each Model
    |--------------------------------------------------------------------------
    |
    | Amount of seeds that are seeded as default when seeding specific Model.
    |
    */

    'models' => [
        'amounts' => [
            'users' => 10,
        ],
    ],

];
