<?php
    date_default_timezone_set('Asia/Manila');

    define('DB_HOSTNAME', 'localhost');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', '');
    define('DB_NAME', 'ppfms_db');
    define('DB_DUMPER', 'C:\xampp\mysql\bin\mysqldump.exe');

    $connection = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $today = date('Y-m-d H:i:s');

    function restoreSettings() {
        global $connection;

        $settings = [
            'shipping_fee_within_metro_manila',
            'shipping_fee_outside_metro_manila',
            'critical_level'
        ]

        $settingsQuery = mysqli_query($connection, "SELECT * FROM `settings`");

        if(mysqli_num_rows($settingsQuery) > 0) {
            while($settingsRow = mysqli_fetch_assoc($settingsQuery)) {
                $index = array_search($settingsRow['name'], $settings);

                if($index !== false) {
                    unset($settings[$index]);
                }
            }
        }

        foreach($settings as $setting) {
            mysqli_query($connection, "INSERT INTO `settings` (`name`, `value`) VALUES ('$setting', '1')")
        }
    }

    restoreSettings();
?>
