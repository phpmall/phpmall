<?php

return [
    'dist' => base_path(),
    'domain_mode' => true,
    'exclude_tables' => [
        'cache',
        'cache_locks',
        'failed_jobs',
        'jobs',
        'job_batches',
        'migrations',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
    ],
    'exclude_columns' => [
        'created_at',
        'updated_at',
        'deleted_at',
        'user' => [
            'xxxx',
        ],
    ],
    'exclude_controllers' => [
        'base',
    ],
    'exclude_views' => [
        'login',
    ],
    'convert_to_singular' => true,
];
