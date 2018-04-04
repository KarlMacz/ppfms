<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];
        $id = input_escape_string($connection, $_POST['id']);
        $issue = input_escape_string($connection, $_POST['issue']);

        $query = mysqli_query($connection, "INSERT INTO `issues` (`inventory_id`, `issue`)
            VALUES ('$id', '$issue')");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Issue has been registered.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to register issue.'
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
