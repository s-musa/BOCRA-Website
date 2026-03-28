<?php

return [
    'modules' => [
        'Restaurant' => [
            'restaurants-workspace' => 'a',
            'rooms-workspace' => 'a',
            'rooms' => 'c,e,d',
            'tables-workspace' => 'a',
            'tables' => 'c,e,d',
            'reviews-workspace' => 'a',
            'restaurant-configurations-workspace' => 'a',
        ],
    ],
    
    'permissions_map' => [
        'a' => 'access',
        'c' => 'add',
        'e' => 'edit',
        'r' => 'view',
        'd' => 'delete',
    ],
];
