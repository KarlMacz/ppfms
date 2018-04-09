<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $settings = [];

        $query = mysqli_query($connection, "SELECT * FROM `settings`");

        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
                $settings[$row['name']] = $row['value'];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Settings has been retrieved.',
                'data' => $settings
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to retrieve settings.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'Error',
            'type' => 'prompt',
            'message' => 'Unauthorized access.'
        ]);
    }

    exit();
?>
