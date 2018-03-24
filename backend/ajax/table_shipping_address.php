<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `shipping_addresses` WHERE `account_id`='$userID'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $infos = [];

            $query = mysqli_query($connection, "SELECT * FROM `shipping_addresses` WHERE `account_id`='$userID' LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                if($row['middle_name'] != null) {
                    $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
                } else {
                    $fullName = $row['first_name'] . ' ' . $row['last_name'];
                }

                $infos[] = [
                    'name' => $fullName,
                    'address' => $row['shipping_address'],
                    'actions' => '<button type="button" class="remove-shipping-address-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove Shipping Address" data-id="' . $row['id'] . '"><span class="fas fa-times fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' shipping address(es) retrieved.',
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
