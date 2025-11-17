<?php

return [
    'model' => [
        'label' => 'Asset',
        'plural_label' => 'Assets',
        'navigation_label' => 'Assets',
        'navigation_group' => 'Resources',
    ],

    'form' => [
        'heading' => 'Assets',
        'fields' => [
            'name' => [
                'label' => 'Name',
            ],
            'asset_category' => [
                'label' => 'Category',
            ],
            'price_cents' => [
                'label' => 'Price',
            ]
        ]
    ],

    'table' => [
        'heading' => 'Assets',
        'columns' => [
            'name' => [
                'label' => 'Name',
            ],
            'price_cents' => [
                'label' => 'Price',
            ]
        ],
        'filters' => [
            'status' => [
                'label' => 'Status',
                'true_label' => 'Active',
                'false_label' => 'closed',
                'placeholder' => 'All',
            ]
        ],
        'actions' => [
            'take_profit' => [
                'label' => 'Take Profit',
                'icon' => 'heroicon-o-banknotes',
                'form' => [
                    'fields' => [
                        'take_profit_cents' => [
                            'label' => 'Realized Amount',
                        ]
                    ]
                ],
                'success_notification' => [
                    'title' => 'Profit_taken',
                ]
            ]
        ],
    ],
];
