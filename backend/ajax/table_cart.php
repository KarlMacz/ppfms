<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $totalAmount = 0;
        $userID = $_SESSION['user_id'];
        $products = [];

        $query = mysqli_query($connection, "SELECT *,
                `carts`.`id` AS `cart_id`
            FROM `carts`
            INNER JOIN `products`
                ON `carts`.`product_id`=`products`.`id`
            WHERE `carts`.`account_id`='$userID'");
        $count = mysqli_num_rows($query);

        if(mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {
                $itemTotal = ((double) $row['quantity']) * $row['item_price'];
                $totalAmount += $itemTotal;

                $products[] = [
                    'name' => $row['name'],
                    'quantity' => $row['quantity'],
                    'total' => number_format($itemTotal, 2),
                    'actions' => '<button type="button" class="edit-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Edit Quantity" data-id="' . $row['cart_id'] . '" data-name="' . $row['name'] . '" data-quantity="' . $row['quantity'] . '" data-available="' . $row['quantity_available'] . '"><span class="fas fa-edit fa-fw"></span></button>
                        <button type="button" class="remove-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove from Cart" data-id="' . $row['cart_id'] . '"><span class="fas fa-trash fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' product(s) retrieved.',
                'data_total_count' => $count,
                'data' => $products,
                'data2' => number_format($totalAmount, 2)
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
