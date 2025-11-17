<?php

return [
    'model' => [
        'label' => 'Todo',
        'plural_label' => 'Todos',
        'navigation_group' => 'Resources',
        'navigation_label' => 'Todos',
    ],
    'tabs' => [
        'open_todos' => [
            'label' => 'Open Todos',
            'plural_label' => 'Open Todos',
            'navigation_label' => 'Open',
        ],
        'completed_todos' => [
            'label' => 'Completed Todos',
            'plural_label' => 'Completed Todos',
            'navigation_label' => 'Completed',
        ],
        'todos' => [
            'form' => [
                'heading' => 'Create todo',
                'fields' => [
                    'title' => [
                        'label' => 'Title',
                    ],
                    'priority' => [
                        'label' => 'Priority',
                    ],
                    'due_date' => [
                        'label' => 'Due',
                        'placeholder' => 'YYYY-MM-DD',
                    ],
                    'category' => [
                        'label' => 'Category',
                        'placeholder' => 'No category',
                    ],
                    'notes' => [
                        'label' => 'Notes',
                    ],
                    'completed_comment' => [
                        'label' => 'Completed Comment',
                    ]
                ],
            ],
            'table' => [
                'heading' => 'Todos',
                'columns' => [
                    'title' => [
                        'label' => 'Title',
                    ],
                    'priority' => [
                        'label' => 'Priority',
                    ],
                    'due_date' => [
                        'label' => 'Due',
                    ],
                    'shared' => [
                        'label' => 'Shared',
                    ],
                    'owner' => [
                        'label' => 'Owner',
                    ],
                    'completed_at' => [
                        'label' => 'Completed',
                    ]
                ],
            ],
            'filters' => [
                'todo_category' => [
                    'label' => 'Category',
                ],
            ],
            'actions' => [
                'create' => [
                    'label' => 'Create new Todo',
                ],
                'complete' => [
                    'label' => 'Complete',
                    'icon' => 'heroicon-o-check',
                    'form' => [
                        'completed_comment' => [
                            'label' => 'Completed Comment',
                        ]
                    ],
                    'success_notification' => [
                        'title' => 'Todo completed.'
                    ]
                ],
                'reopen' => [
                    'label' => 'Reopen',
                    'icon' => 'heroicon-o-arrow-uturn-left',
                    'success_notification' => [
                        'title' => 'Todo reopened.',
                    ]
                ]
            ],
            'priority' => [
                'low' => 'Low',
                'medium' => 'Medium',
                'high' => 'High',
                'extreme' => 'Extreme',
            ]
        ],
    ],
];
