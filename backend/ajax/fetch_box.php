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

            $query = mysqli_query($connection, "UPDATE `inventories` SET `boxes_in_stock`=`boxes_in_stock`-1 WHERE `id`='$id'");

            if(mysqli_affected_rows($connection) === 1) {
                $query = mysqli_query($connection, "UPDATE `products` SET `quantity_available`=`quantity_available`+$quantity WHERE `id`='$productID'");

                if(mysqli_affected_rows($connection) === 1) {
                    echo json_encode([
                        'status' => 'Ok',
                        'type' => 'prompt',
                        'message' => 'Box has been fetched from the inventory.'
                    ]);
                } else {
                    mysqli_query($connection, "UPDATE `inventories` SET `boxes_in_stock`=`boxes_in_stock`+1 WHERE `id`='$id'");

                    echo json_encode([
                        'status' => 'Error',
                        'type' => 'prompt',
                        'message' => 'Failed to fetch box from the inventory.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to fetch box from the inventory.'
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
