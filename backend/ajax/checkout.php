<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];
        $paymentMethod = input_escape_string($connection, $_POST['payment_method']);
        $trackingNumber = generate_tracking_number($connection);
        $billingAddressID = input_escape_string($connection, $_POST['billing_address']);
        $shippingAddressID = input_escape_string($connection, $_POST['shipping_address']);
        $shippingFee = input_escape_string($connection, $_POST['shipping_fee']);

        $query = mysqli_query($connection, "SELECT *,
                `carts`.`id` AS `cart_id`
            FROM `carts`
            INNER JOIN `products`
                ON `carts`.`product_id`=`products`.`id`
            WHERE `carts`.`account_id`='$userID'");

        if(mysqli_num_rows($query)  > 0) {
            $products = [];
            $amountDue = 0;

            while($row = mysqli_fetch_assoc($query)) {
                $itemAmount = ((double) $row['quantity']) * $row['item_price'];
                $products[] = [
                    'cart_id' => $row['cart_id'],
                    'product_id' => $row['product_id'],
                    'quantity' => $row['quantity'],
                    'total_amount' => $itemAmount
                ];
                $amountDue += $itemAmount;
            }

            $order = mysqli_query($connection, "INSERT INTO `orders` (`account_id`, `billing_address_id`, `shipping_address_id`, `tracking_number`, `payment_method`, `shipping_fee`, `amount_due`, `created_at`) VALUES ('$userID', '$billingAddressID', '$shippingAddressID', '$trackingNumber', '$paymentMethod', '$shippingFee', '$amountDue', '$today')");

            if(mysqli_affected_rows($connection) === 1) {
                $orderID = mysqli_insert_id($connection);
                $ctr = 0;

                foreach($products as $product) {
                    $cartID = $product['cart_id'];
                    $productID = $product['product_id'];
                    $quantity = $product['quantity'];
                    $totalAmount = $product['total_amount'];

                    mysqli_query($connection, "INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `total_amount`) VALUES ('$orderID', '$productID', '$quantity', '$totalAmount')");
                    
                    if(mysqli_affected_rows($connection) === 1) {
                        $ctr++;
                    }
                }

                if($ctr > 0) {
                    mysqli_query($connection, "DELETE FROM `carts` WHERE `account_id`='$userID'");

                    echo json_encode([
                        'status' => 'Ok',
                        'type' => 'prompt',
                        'message' => 'Checkout successful.'
                    ]);
                } else {
                    mysqli_query($connection, "DELETE FROM `orders` WHERE `id`='$orderID'");

                    echo json_encode([
                        'status' => 'Error',
                        'type' => 'prompt',
                        'message' => 'Failed to checkout cart items.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to checkout cart items.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Unable to continue checkout. Your cart is empty.'
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
