<?php

return [
    'form' => [
        'heading' => 'Share',
        'fields' => [
            'shared_with' => [
                'label' => 'Share with',
            ]
        ]
    ],

    'table' => [
        'heading' => 'Shared history',
        'columns' => [
            'shared_with' => [
                'label' => 'Shared with',
                'placeholder' => '-'
            ],
            'shared_by' => [
                'label' => 'Shared by',
                'placeholder' => '-'
            ],
            'shared_at' => [
                'label' => 'Shared at',
            ],
            'shared_by_email' => [
                'label' => 'Shared by email',
                'placeholder' => '-',
            ]
        ],
        'actions' => [
            'create' => [
                'label' => 'Share',
                'modal_heading' => 'Share',
            ],
            'delete' => [
                'label' => 'Remove',
                'modal_heading' => 'Remove share',
            ]
        ],
    ],

    'relations' => [
        'past_participle' => 'Shared'
    ]
];
