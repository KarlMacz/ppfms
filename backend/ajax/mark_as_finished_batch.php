<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $batchQuery = mysqli_query($connection, "SELECT * FROM `batches` WHERE `id`='$id'");

        if(mysqli_num_rows($batchQuery) === 1) {
            $batchRow = mysqli_fetch_assoc($batchQuery);
            $productID = $batchRow['product_id'];
            $quantity = $batchRow['quantity'];
            $price = $batchRow['batch_price'];

            $query = mysqli_query($connection, "UPDATE `batches` SET `status`='Finished', `timestamp_finished`='$today' WHERE `id`='$id'");

            if(mysqli_affected_rows($connection) === 1) {
                mysqli_query($connection, "INSERT INTO `inventories` (`product_id`, `batch_id`, `boxes_arrived`, `boxes_in_stock`, `price`)
                    VALUES ('$productID', '$id', '$quantity', '$quantity', '$price')");

                echo json_encode([
                    'status' => 'Ok',
                    'type' => 'prompt',
                    'message' => 'Batch has been marked as finished.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to mark batch as finished.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Product doesn\'t exist.'
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
