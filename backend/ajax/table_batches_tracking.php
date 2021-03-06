<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT *,
                `all_orders`.`created_at` AS `ao_created_at`,
                `all_orders`.`timestamp_finished` AS `ao_timestamp_finished`
            FROM (
                SELECT *, `created_at` AS `log` FROM `batches`
                UNION
                SELECT *, `timestamp_finished` AS `log` FROM `batches`
            ) `all_orders`
            INNER JOIN `products`
                ON `all_orders`.`product_id`=`products`.`id`
            ORDER BY `all_orders`.`log`");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $batches = [];
            $order_logs = [];

            $query = mysqli_query($connection, "SELECT *,
                    `all_orders`.`created_at` AS `ao_created_at`,
                    `all_orders`.`timestamp_finished` AS `ao_timestamp_finished`
                FROM (
                    SELECT *, `created_at` AS `log` FROM `batches`
                    UNION
                    SELECT *, `timestamp_finished` AS `log` FROM `batches`
                ) `all_orders`
                INNER JOIN `products`
                    ON `all_orders`.`product_id`=`products`.`id`
                ORDER BY `all_orders`.`log`
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $inTimestamp = '';
                $outTimestamp = '';

                if(!in_array($row['batch_number'], $order_logs)) {
                    $inTimestamp = date('F d, Y h:iA', strtotime($row['ao_created_at']));
                }

                if(in_array($row['batch_number'], $order_logs)) {
                    $outTimestamp = date('F d, Y h:iA', strtotime($row['ao_timestamp_finished']));
                }

                $batches[] = [
                    'number' => $row['batch_number'],
                    'product' => $row['name'],
                    'quantity' => $row['quantity'] . ' boxes',
                    'in' => $inTimestamp,
                    'out' => $outTimestamp
                ];

                $order_logs[] = $row['batch_number'];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' batch(es) retrieved.',
                'data_total_count' => $count,
                'data' => $batches
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
