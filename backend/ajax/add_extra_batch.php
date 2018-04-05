<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);
        $information = input_escape_string($connection, $_POST['information']);

        $query = mysqli_query($connection, "INSERT INTO `batch_information` (`batch_id`, `additional_information`, `created_at`) VALUES ('$id', '$information', '$today')");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Batch information has been added.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to add batch information.'
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
