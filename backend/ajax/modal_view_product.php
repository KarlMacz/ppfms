<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `products` WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $outputBody = '<div class="text-center">
                    ' . ($row['image'] != null ? '<img src="../img/' . $row['image'] . '">' : '<div class="alert alert-info">No image preview.</div>') . '
                </div>
                <h2>' . $row['name'] . '</h2>
                <p>' . nl2br($row['description']) . '</p>
                <h3 class="text-right">Php ' . number_format($row['item_price'], 2) . '</h3>';
            $outputFooter = '<div class="text-right">
                    <button class="add-to-wishlist-button btn btn-danger" data-id="' . $row['id'] . '"><span class="fas fa-heart fa-fw"></span> Add to Wishlist</button>
                    <button class="add-to-cart-button btn btn-primary" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-available="' . $row['quantity_available'] . '"><span class="fas fa-shopping-cart fa-fw"></span> Add to Cart</button>
                </div>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Product information has been retrieved.',
                'output' => [
                    'body' => $outputBody,
                    'footer' => $outputFooter,
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'No results found.'
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
