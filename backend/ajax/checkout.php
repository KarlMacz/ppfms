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
                    'product_name' => $row['name'],
                    'quantity' => $row['quantity'],
                    'total_amount' => $itemAmount
                ];
                $amountDue += $itemAmount;
            }

            $order = mysqli_query($connection, "INSERT INTO `orders` (`account_id`, `billing_address_id`, `shipping_address_id`, `tracking_number`, `payment_method`, `shipping_fee`, `amount_due`, `created_at`) VALUES ('$userID', '$billingAddressID', '$shippingAddressID', '$trackingNumber', '$paymentMethod', '$shippingFee', '$amountDue', '$today')");

            if(mysqli_affected_rows($connection) === 1) {
                $orderID = mysqli_insert_id($connection);
                $ctr = 0;
                $boomyAmount = 0;

                $tbodyContent = '';

                foreach($products as $product) {
                    $cartID = $product['cart_id'];
                    $productID = $product['product_id'];
                    $quantity = $product['quantity'];
                    $totalAmount = $product['total_amount'];

                    $boomyAmount += $totalAmount;

                    mysqli_query($connection, "INSERT INTO `order_items` (`order_id`, `product_id`, `quantity`, `total_amount`) VALUES ('$orderID', '$productID', '$quantity', '$totalAmount')");
                    
                    if(mysqli_affected_rows($connection) === 1) {
                        $tbodyContent .= '<tr>
                                <td>' . $row['product_name'] . '</td>
                                <td>' . $totalAmount . '</td>
                            </tr>';

                        $ctr++;
                    }
                }

                if($ctr > 0) {
                    mysqli_query($connection, "DELETE FROM `carts` WHERE `account_id`='$userID'");

                    if($paymentMethod === 'Cash on Delivery') {
                        send_email($_SESSION['email'], 'Order #' . $trackingNumber . ' Checkout Details', '<!DOCTYPE html>
                            <html lang="en">
                            <head>
                                <meta charset="UTF-8">
                                <title>' . COMPANY_NAME . '</title>
                                <style>
                                    body { font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif; }
                                    h1, h2, h3, h4, h5, h6 { margin 0 0 10px 0; }
                                    p { margin: 0; }
                                    .content p { margin: 10px 0; }
                                    table { width: 100%; }
                                </style>
                            </head>
                            <body>
                                <h3>Dear ' . $_SESSION['first_name'] . ',</h3>
                                <div class="content">
                                    <p>Greetings from BITC Cosmetics!</p>
                                    <p>This is to confirm your product purchase with the following detail:</p>
                                    <p>Please be advice that the official receipt will be provided by the delivery man after you receive your product.</p>
                                    <table>
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Price</th>
                                            </tr>
                                        </thead>
                                        <tbody>' . $tbodyContent . '</tbody>
                                        <tfoot>
                                            <tr>
                                                <th>Total:</th>
                                                <th>' . $boomyAmount . '</th>
                                            </tr>
                                            <tr>
                                                <th>Shipping Fee:</th>
                                                <th>' . $shippingFee . '</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <p>Thank you for choosing BITC Cosmetics.</p>
                                </div>
                            </body>
                            </html>', $_SESSION['full_name']);
                    }

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
