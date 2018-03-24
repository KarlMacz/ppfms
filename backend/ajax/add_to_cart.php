<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];
        $id = input_escape_string($connection, $_POST['id']);
        $quantity = input_escape_string($connection, $_POST['quantity']);

        $query = mysqli_query($connection, "SELECT * FROM `products` WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            if($row['quantity_available'] >= $quantity) {
                $query = mysqli_query($connection, "SELECT * FROM `carts` WHERE `account_id`='$userID' AND `product_id`='$id'");

                if(mysqli_num_rows($query) === 0) {
                    $query = mysqli_query($connection, "INSERT INTO `carts` (`account_id`, `product_id`, `quantity`, `created_at`) VALUES ('$userID', '$id', '$quantity', '$today')");

                    if(mysqli_affected_rows($connection) === 1) {
                        echo json_encode([
                            'status' => 'Ok',
                            'type' => 'prompt',
                            'message' => 'Product has been added to cart.'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'Error',
                            'type' => 'prompt',
                            'message' => 'Failed to add product to cart.'
                        ]);
                    }
                } else {
                    $row = mysqli_fetch_assoc($query);
                    $cartID = $row['id'];

                    $query = mysqli_query($connection, "UPDATE `carts` SET  `quantity`='$quantity', `created_at`='$today' WHERE `id`='$cartID'");

                    if(mysqli_affected_rows($connection) === 1) {
                        echo json_encode([
                            'status' => 'Ok',
                            'type' => 'prompt',
                            'message' => 'Product has been added to cart.'
                        ]);
                    } else {
                        echo json_encode([
                            'status' => 'Error',
                            'type' => 'prompt',
                            'message' => 'Failed to add product to cart.'
                        ]);
                    }
                }
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Can\'t add product to cart due to exceeding quantity.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Product doesn\'t exist anymore.'
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
