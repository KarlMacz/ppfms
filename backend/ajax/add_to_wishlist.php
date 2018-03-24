<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `wishlists` WHERE `account_id`='$userID' AND `product_id`='$id'");

        if(mysqli_num_rows($query) === 0) {
            $query = mysqli_query($connection, "INSERT INTO `wishlists` (`account_id`, `product_id`, `created_at`) VALUES ('$userID', '$id', '$today')");

            if(mysqli_affected_rows($connection) === 1) {
                echo json_encode([
                    'status' => 'Ok',
                    'type' => 'prompt',
                    'message' => 'Product has been added to wishlist.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to add product to wishlist.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Product has already been added to wishlist.'
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
