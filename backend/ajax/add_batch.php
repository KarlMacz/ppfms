<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $product = input_escape_string($connection, $_POST['product']);
        $quantity = input_escape_string($connection, $_POST['quantity']);
        $batchNumber = generate_batch_number($connection);

        $query = mysqli_query($connection, "INSERT INTO `batches` (`product_id`, `supplier_id`, `batch_number`, `quantity`, `created_at`) VALUES ('$product', NULL, '$batchNumber', '$quantity', '$today')");

        if(mysqli_affected_rows($connection) === 1) {
            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Batch has been added.'
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to add batch. Error: ' . mysqli_error($connection)
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
