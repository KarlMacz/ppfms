<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];
        $firstName = input_escape_string($connection, $_POST['first_name']);
        $middleName = isset($_POST['middle_name']) && $_POST['middle_name'] !== '' ? input_escape_string($connection, $_POST['middle_name']) : null;
        $lastName = input_escape_string($connection, $_POST['last_name']);
        $address = input_escape_string($connection, $_POST['address']);

        $query = mysqli_query($connection, "SELECT * FROM `shipping_addresses` WHERE `first_name`='$firstName' AND `last_name`='$lastName' AND `shipping_address`='$address' AND `account_id`='$userID'");

        if(mysqli_num_rows($query) === 0) {
            if($middleName != null) {
                $query = mysqli_query($connection, "INSERT INTO `shipping_addresses` (`account_id`, `first_name`, `middle_name`, `last_name`, `shipping_address`) VALUES ('$userID', '$firstName', '$middleName', '$lastName', '$address')");
            } else {
                $query = mysqli_query($connection, "INSERT INTO `shipping_addresses` (`account_id`, `first_name`, `last_name`, `shipping_address`) VALUES ('$userID', '$firstName', '$lastName', '$address')");
            }

            if(mysqli_affected_rows($connection) === 1) {
                echo json_encode([
                    'status' => 'Ok',
                    'type' => 'prompt',
                    'message' => 'Shipping Address has been added.'
                ]);
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Failed to add shipping address.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Shipping Address under the same name and address already exist.'
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
