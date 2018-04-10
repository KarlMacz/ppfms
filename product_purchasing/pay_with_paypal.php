<?php
    require_once('../backend/database.php');
    require_once('../backend/functions.php');

    if(isset($_GET['id'])) {
        $id = input_escape_string($connection, $_GET['id']);

        $ppDeal = new PayPalDeal();

        $query = mysqli_query($connection, "SELECT * FROM `orders`
            WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            if($row['payment_method'] === 'PayPal') {
                $query2 = mysqli_query($connection, "SELECT * FROM `order_items`
                    INNER JOIN `products`
                        ON `order_items`.`product_id`=`products`.`id`
                    WHERE `order_items`.`order_id`='$id'");

                $items = [];

                while($row2 = mysqli_fetch_assoc($query2)) {
                    $items[] = [
                        'name' => $row2['name'],
                        'quantity' => $row2['quantity'],
                        'price' => $row2['total_amount'],
                    ];
                }

                $ppDealTransaction = $ppDeal->make_transaction('Order #' . $row['tracking_number'], $items, $row['shipping_fee'], '/product_purchasing/verify_paypal_payment.php?status_code=1&tracking_number='. $row['tracking_number'], '/product_purchasing/verify_paypal_payment.php?status_code=0');

                header('Location: ' . $ppDealTransaction);
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Your payment method does not match with what you are trying to do.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to remove supplier\'s information.'
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
