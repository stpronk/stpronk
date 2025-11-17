<?php

return [
    'model' => [
        'label' => 'Asset Dashboard',
        'plural_label' => 'Asset Dashboards',
        'navigation_label' => 'Dashboard',
    ],

    'headers' => [
        'active_assets' => 'Active Assets',
        'total_invested' => 'Total Invested (Active)',
        'net_realized' => 'Net Realized (P/L)',
    ],

    'tables' => [
        'realized_results' => [
            'label' => 'Realized Results',
            'columns' => [
                'earned' => 'Earned',
                'lost' => 'Lost',
                'net' => 'Net',
            ]
        ],
        'active_assets' => [
            'label' => 'Active Assets',
            'columns' => [
                'name' => 'Name',
                'category' => 'Category',
                'price' => 'Price',
            ],
            'empty' => 'No active assets found.',
        ],
        'realized_assets' => [
            'label' => 'Realized Assets',
            'columns' => [
                'name' => 'Name',
                'category' => 'Category',
                'bought' => 'Bought',
                'sold' => 'Sold',
                'p_l' => 'P/L'
            ],
            'empty' => 'No realized Assets found.',
        ]
    ]
];
