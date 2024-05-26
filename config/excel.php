<?php

return [
    'exports' => [
        'chunk_size'             => 1000,
        'pre_calculate_formulas' => false,
        'strict_null_comparison' => false,
        'csv'                    => [
            'delimiter'              => ',',
            'enclosure'              => '"',
            'line_ending'            => "\n",
            'use_bom'                => false,
            'include_separator_line' => false,
            'excel_compatibility'    => false,
        ],
        'properties' => [
            'creator'        => '',
            'last_modified_by' => '',
            'title'          => '',
            'description'    => '',
            'subject'        => '',
            'keywords'       => '',
            'category'       => '',
            'manager'        => '',
            'company'        => '',
        ],
    ],

    'imports' => [
        'read_only' => true,
        'heading'   => true,
        'csv'       => [
            'delimiter'        => ',',
            'enclosure'        => '"',
            'escape_character' => '\\',
            'contiguous'       => false,
            'input_encoding'   => 'UTF-8',
        ],
        'properties' => [
            'creator'        => '',
            'last_modified_by' => '',
            'title'          => '',
            'description'    => '',
            'subject'        => '',
            'keywords'       => '',
            'category'       => '',
            'manager'        => '',
            'company'        => '',
        ],
    ],

    'heading_row' => [
        'formatter' => 'slug',
    ],

    'rows' => [
        'limit' => null,
    ],

    'temporary_files' => [
        'local_path'  => storage_path('framework/laravel-excel'),
        'remote_disk' => null,
        'remote_prefix' => null,
        'force_resync_remote' => null,
    ],
];
