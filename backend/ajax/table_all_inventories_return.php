<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `inventories`
            INNER JOIN `products`
                ON `inventories`.`product_id`=`products`.`id`
            INNER JOIN `suppliers`
                ON `inventories`.`supplier_id`=`suppliers`.`id`
            WHERE `inventories`.`status`='Returned'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $infos = [];

            $query = mysqli_query($connection, "SELECT *,
                    `products`.`name` AS `product_name`,
                    `products`.`description` AS `product_description`,
                    `suppliers`.`name` AS `supplier_name`,
                    `suppliers`.`description` AS `supplier_description`
                FROM `inventories`
                INNER JOIN `products`
                    ON `inventories`.`product_id`=`products`.`id`
                INNER JOIN `suppliers`
                    ON `inventories`.`supplier_id`=`suppliers`.`id`
                WHERE `inventories`.`status`='Returned'
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $infos[] = [
                    'name' => $row['product_name'],
                    'description' => $row['product_description'],
                    'supplier' => $row['supplier_name'],
                    'reason' => ($row['return_reason'] != null ? $row['return_reason'] : ''),
                    'actions' => ''
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' stock(s) retrieved.',
                'data_total_count' => $count,
                'data' => $infos
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
