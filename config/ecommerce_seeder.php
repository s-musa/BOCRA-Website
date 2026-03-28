<?php

return [
    'modules' => [
        'Ecommerce' => [
            'shops-workspace' => 'a',
            'shops' => 'e',
            'products-workspace' => 'a',
            'product' => 'c,e,d',
            'orders-workspace' => 'a',
            'orders' => 'r,d',
            'payments-workspace' => 'a',
            'payments' => 'r,d',
            'configurations-workspace' => 'a',
            'configure-categories' => 'c,e,d',
            'configure-variants' => 'c,e,d',
            'configure-payment-methods' => 'c,e,d',
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
