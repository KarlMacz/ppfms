<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `accounts`
            INNER JOIN `users`
                ON `accounts`.`id`=`users`.`account_id`
            WHERE `accounts`.`id`='$id' AND `accounts`.`type`='Client'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            if($row['middle_name'] != null) {
                $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
            } else {
                $fullName = $row['first_name'] . ' ' . $row['last_name'];
            }

            $outputBody = '<h2 class="no-margin">' . $fullName . '</h2>
                <div style="margin-bottom: 10px;">' . $row['email'] . '</div>
                <div>' . $row['gender'] . ' | ' . date('F d, Y', strtotime($row['birth_date'])) . '</div>
                <div>' . $row['address'] . '</div>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Buyer\'s information has been retrieved.',
                'output' => [
                    'body' => $outputBody
                ]
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
