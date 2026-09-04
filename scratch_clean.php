<?php
$files = glob('/home/lilac-pc-12/Desktop/projects/laravel-learning/database/migrations/*.php');
$exclude = [
    '0001_01_01_000000_create_users_table.php',
    '0001_01_01_000001_create_cache_table.php',
    '0001_01_01_000002_create_jobs_table.php',
    '2024_01_01_000000_create_passkeys_table.php',
    '2026_09_03_100001_create_departments_table.php',
    '2026_09_03_100002_add_employee_fields_to_users_table.php',
    '2026_09_03_100006_create_notifications_table.php',
    '2026_09_03_100007_create_personal_access_tokens_table.php'
];

foreach ($files as $file) {
    $basename = basename($file);
    if (!in_array($basename, $exclude)) {
        unlink($file);
    }
}
file_put_contents('/home/lilac-pc-12/Desktop/projects/laravel-learning/database/database.sqlite', '');
echo "Cleaned up successfully.";
