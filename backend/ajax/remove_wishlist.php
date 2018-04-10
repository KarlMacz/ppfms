<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "DELETE FROM `wishlists` WHERE `id`='$id'");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Product has been removed from wishlist.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to remove product from wishlist.'
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