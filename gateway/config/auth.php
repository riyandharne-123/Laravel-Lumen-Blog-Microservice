<?php

return [
    'defaults' => [
        'guard' => 'api'
    ],

    'guards' => [
        'api' => [
            'driver' => 'jwt'
        ],
    ],

];
