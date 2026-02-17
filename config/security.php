<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains security-related configuration options for the
    | dental care SaaS application.
    |
    */

    'rate_limiting' => [
        'login_attempts' => 5,
        'login_decay_minutes' => 15,
        'patient_login_attempts' => 5,
        'patient_login_decay_minutes' => 10,
        'api_requests_per_minute' => 60,
    ],

    'session' => [
        'patient_timeout_hours' => 2,
        'admin_timeout_hours' => 8,
        'force_logout_on_ip_change' => true,
    ],

    'password_requirements' => [
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_special_chars' => true,
        'max_age_days' => 90,
    ],

    'input_validation' => [
        'max_string_length' => 1000,
        'max_text_length' => 5000,
        'allowed_file_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'max_file_size_mb' => 10,
    ],

    'audit_logging' => [
        'enabled' => env('AUDIT_LOGGING_ENABLED', true),
        'log_failed_logins' => true,
        'log_data_changes' => true,
        'log_admin_actions' => true,
        'retention_days' => 365,
    ],

    'encryption' => [
        'sensitive_fields' => [
            'phone',
            'address',
            'medical_history',
            'allergies',
            'insurance_number',
        ],
    ],

    'access_control' => [
        'superadmin_ips' => array_filter(explode(',', env('SUPERADMIN_IPS', ''))),
        'maintenance_mode_bypass_ips' => array_filter(explode(',', env('MAINTENANCE_BYPASS_IPS', ''))),
        'block_tor_exit_nodes' => (bool) env('BLOCK_TOR_NODES', false),
        'require_2fa_for_admin' => (bool) env('REQUIRE_2FA_ADMIN', false),
    ],

    'data_protection' => [
        'anonymize_deleted_patients' => true,
        'data_retention_years' => 7,
        'automatic_backup_enabled' => true,
        'backup_encryption_enabled' => true,
    ],
];