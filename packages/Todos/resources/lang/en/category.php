<?php
return [
    'model' => [
        'label' => 'Todo Category',
        'plural_label' => 'Todo Categories',
        'navigation_label' => 'Categories',
    ],
    'form' => [
        'fields' => [
            'name' => [
                'label' => 'Name',
            ],
            'color' => [
                'label' => 'Badge color',
                'placeholder' => 'primary',
                'options' => [
                    'Red' => 'Red',
                    'Blue' => 'Blue',
                    'Yellow' => 'Yellow',
                    'Emerald' => 'Emerald',
                    'Amber' => 'Amber',
                    'Zinc' => 'Zinc',
                ]
            ]
        ]
    ],
    'table' => [
        'columns' => [
            'name' => [
                'label' => 'Name',
            ],
            'open_todos_count' => [
                'label' => 'Open',
            ],
            'complete_todos_count' => [
                'label' => 'Completed',
            ],
            'created_at' => [
                'label' => 'Created',
            ]
        ]
    ]
];
