<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `batches`
            INNER JOIN `products`
                ON `batches`.`product_id`=`products`.`id`");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $batches = [];

            $query = mysqli_query($connection, "SELECT *,
                    `batches`.`id` AS `batch_id`,
                    `batches`.`created_at` AS `batch_created_at`
                FROM `batches`
                INNER JOIN `products`
                    ON `batches`.`product_id`=`products`.`id`
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                if($row['status'] === 'Processing') {
                    $actions = '<button type="button" class="extra-button btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Extra Product Information" data-id="' . $row['batch_id'] . '"><span class="fas fa-plus fa-fw"></span></button>
                        <button type="button" class="finished-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Mark Batch as Finished" data-id="' . $row['batch_id'] . '"><span class="fas fa-check fa-fw"></span></button>';
                } else {
                    $actions = '<button type="button" class="extra-button btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Extra Product Information" data-id="' . $row['batch_id'] . '"><span class="fas fa-plus fa-fw"></span></button>';
                }

                $batches[] = [
                    'number' => $row['batch_number'],
                    'product' => $row['name'],
                    'datetime_added' => date('F d, Y h:iA', strtotime($row['batch_created_at'])),
                    'status' => $row['status'],
                    'actions' => $actions
                ];
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
