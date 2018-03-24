<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $cartID = input_escape_string($connection, $_POST['cart_id']);
        $quantity = input_escape_string($connection, $_POST['quantity']);

        $query = mysqli_query($connection, "UPDATE `carts` SET  `quantity`='$quantity', `created_at`='$today' WHERE `id`='$cartID'");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Product quantity has been updated.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to update product quantity.'
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
