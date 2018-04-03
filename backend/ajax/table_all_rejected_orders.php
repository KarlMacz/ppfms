<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `orders`
            WHERE `status` IN ('Cancelled', 'Returned')");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $orders = [];

            $query = mysqli_query($connection, "SELECT * FROM `orders`
                INNER JOIN `accounts`
                    ON `orders`.`account_id`=`accounts`.`id`
                INNER JOIN `users`
                    ON `accounts`.`id`=`users`.`account_id`
                WHERE `orders`.`status` IN ('Cancelled', 'Returned')
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $orderID = $row['id'];
                $orderItems = '';

                if($row['middle_name'] != null) {
                    $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
                } else {
                    $fullName = $row['first_name'] . ' ' . $row['last_name'];
                }

                $query2 = mysqli_query($connection, "SELECT * FROM `order_items`
                    INNER JOIN `products`
                        ON `order_items`.`product_id`=`products`.`id`");

                if(mysqli_num_rows($query2) > 0) {
                    while($row2 = mysqli_fetch_assoc($query2)) {
                        $orderItems .= '<div style="padding-left: 20px; text-indent: -20px;"><strong>x' . $row2['quantity'] . '</strong> ' . $row2['name'] . '</div>';
                    }
                }

                $orders[] = [
                    'tracking_number' => $row['tracking_number'],
                    'products' => $orderItems,
                    'buyer' => $fullName,
                    'datetime_ordered' => date('F d, Y h:iA', strtotime($row['created_at'])),
                    'reason' => $row['rejection_reason'],
                    'actions' => ''
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' order(s) retrieved.',
                'data_total_count' => $count,
                'data' => $orders
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
