<?php
    date_default_timezone_set('Asia/Manila');

    define('DB_HOSTNAME', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'ppfms_db');

    $connection = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $today = date('Y-m-d H:i:s');
?>
