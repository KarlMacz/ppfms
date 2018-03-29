<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "DELETE FROM `carts` WHERE `account_id`='$userID'");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'All products have been removed from cart.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to remove all products from cart.'
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
