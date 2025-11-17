<?php
return [
    'model' => [
        'label' => 'Asset Category',
        'plural_label' => 'Asset Categories',
        'navigation_label' => 'Categories',
    ],
    'form' => [
        'fields' => [
            'name' => [
                'label' => 'Name',
            ],
            'color' => [
                'label' => 'Badge color',
                'options' => [
                    'Red' => 'Red',
                    'Blue' => 'Blue',
                    'Yellow' => 'Yellow',
                    'Emerald' => 'Emerald',
                    'Amber' => 'Amber',
                    'Zinc' => 'Zinc'
                ]
            ]
        ]
    ],
    'table' => [
        'columns' => [
            'name' => [
                'label' => 'Name',
            ],
            'assets_count' => [
                'label' => 'Assets',
            ],
        ]
    ]
];
