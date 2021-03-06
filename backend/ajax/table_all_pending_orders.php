<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `orders`
            WHERE `status`='Pending'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $orders = [];

            $query = mysqli_query($connection, "SELECT * FROM `orders`
                WHERE `status`='Pending'
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $orderID = $row['id'];
                $orderItems = '';

                $query2 = mysqli_query($connection, "SELECT * FROM `order_items`
                    INNER JOIN `products`
                        ON `order_items`.`product_id`=`products`.`id`
                    WHERE `order_items`.`order_id`='$orderID'");

                if(mysqli_num_rows($query2) > 0) {
                    while($row2 = mysqli_fetch_assoc($query2)) {
                        $orderItems .= '<div style="padding-left: 20px; text-indent: -20px;"><strong>x' . $row2['quantity'] . '</strong> ' . $row2['name'] . '</div>';
                    }
                }

                $orders[] = [
                    'tracking_number' => $row['tracking_number'],
                    'products' => $orderItems,
                    'datetime_ordered' => date('F d, Y h:iA', strtotime($row['created_at'])),
                    'status' => $row['status'],
                    'actions' => '<button type="button" class="dispatch-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Order Registry" data-id="' . $orderID . '"><span class="fas fa-check fa-fw"></span></button>
                        <button type="button" class="cancel-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Cancel Order" data-id="' . $orderID . '"><span class="fas fa-times fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' product(s) retrieved.',
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
