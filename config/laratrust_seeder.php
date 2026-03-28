<?php

return [
    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'modules' => [
        'Customisation' => [
            'customisation-workspace' => 'a',
            'customisation' => 'c,e,d',
        ],
        'Component-Management' => [
            'components-workspace' => 'a',
            'services' => 'c,e,d',
            'projects' => 'c,e,d',
            'board-members' => 'c,e,d',
            'partners' => 'c,e,d',
            'products' => 'c,e,d',
            'faqs' => 'c,e,d',
            'skills' => 'c,e,d',
        ],
        'Page-Management' => [
            'page-workspace' => 'a',
            'page' => 'c,e,d',
            'page-sections' => 'a'
        ],
        'Menu-Management' => [
            'menu-workspace' => 'a',
            'menu' => 'c,e,d',
        ],
        'Settings' => [
            'company-workspace' => 'a',
            'company' => 'c,e',
        ],
        'User-Management' => [
            'users-workspace' => 'a',
            'users' => 'c,e,r,d',
            'roles-workspace' => 'a',
            'roles' => 'c,e,d',
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
