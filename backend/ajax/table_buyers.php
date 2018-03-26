<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $query = mysqli_query($connection, "SELECT * FROM `accounts`
            INNER JOIN `users`
                ON `accounts`.`id`=`users`.`account_id`
            WHERE `accounts`.`type`='Client'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $buyers = [];

            $query = mysqli_query($connection, "SELECT * FROM `accounts`
                INNER JOIN `users`
                    ON `accounts`.`id`=`users`.`account_id`
                WHERE `accounts`.`type`='Client'
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                if($row['middle_name'] != null) {
                    $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
                } else {
                    $fullName = $row['first_name'] . ' ' . $row['last_name'];
                }

                $buyers[] = [
                    'full_name' => $fullName,
                    'actions' => '<button type="button" class="view-buyer-button btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="View Buyer\'s Information" data-id="' . $row['account_id'] . '"><span class="fas fa-bars fa-fw"></span></button>
                        <button type="button" class="delete-buyer-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Buyer\'s Information" data-id="' . $row['account_id'] . '"><span class="fas fa-trash fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' buyer(s) retrieved.',
                'data_total_count' => $count,
                'data' => $buyers
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
