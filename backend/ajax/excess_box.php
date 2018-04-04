<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);
        $quantity = input_escape_string($connection, $_POST['quantity']);

        $query = mysqli_query($connection, "SELECT * FROM `inventories` WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $productID = $row['product_id'];

            $query = mysqli_query($connection, "UPDATE `inventories` SET `excess_boxes`=`excess_boxes`+$quantity, `boxes_in_stock`=`boxes_in_stock`-$quantity WHERE `id`='$id'");

            if(mysqli_affected_rows($connection) === 1) {
                echo json_encode([
                    'status' => 'Ok',
                    'type' => 'prompt',
                    'message' => 'Box has been marked as excess.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to mark box as excess.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Stock doesn\'t exist.'
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
