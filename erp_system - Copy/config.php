<?php
// Local Database configuration (XAMPP)
define('DB_HOST', 'localhost');
define('DB_USER', 'root'); // Default XAMPP username
define('DB_PASSWORD', ''); // Default XAMPP password is empty
define('DB_NAME', 'erp_system');

// AWS S3 configuration will be added later
define('AWS_ACCESS_KEY_ID', ''); 
define('AWS_SECRET_ACCESS_KEY', '');
define('AWS_REGION', '');
define('S3_BUCKET', '');

// Application configuration
define('APP_URL', 'http://localhost');
define('UPLOAD_DIR', 'uploads/');

// Free Tier Limits (for future AWS use)
define('S3_FREE_TIER_LIMIT', 5 * 1024 * 1024 * 1024); // 5GB in bytes
define('MONTHLY_PUT_LIMIT', 2000);
define('MONTHLY_GET_LIMIT', 20000);
?> 