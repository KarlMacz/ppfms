<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $requestCode = input_escape_string($connection, $_POST['request_code']);
        $newPassword = input_escape_string($connection, $_POST['new_password']);
        $confirmNewPassword = input_escape_string($connection, $_POST['confirm_new_password']);

        $newPassword = hash('sha256', $newPassword);
        $confirmNewPassword = hash('sha256', $confirmNewPassword);

        $query = mysqli_query($connection, "SELECT * FROM `accounts`
            WHERE `change_password_code`='$requestCode'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $accountID = $row['id'];

            if(hash_equals($newPassword, $confirmNewPassword)) {
                $accountQuery = mysqli_query($connection, "UPDATE `accounts` SET `password`='$newPassword', `change_password_code`=NULL WHERE `id`='$accountID'");

                if(mysqli_affected_rows($connection) === 1) {
                    echo json_encode([
                        'status' => 'Ok',
                        'type' => 'prompt',
                        'message' => 'Change password successful.',
                        'data' => [
                            'url' => 'login.php'
                        ]
                    ]);
                } else {
                    echo json_encode([
                        'status' => 'Error',
                        'type' => 'prompt',
                        'message' => 'Failed to change password.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'New Password and Confirm New Password don\'t match.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Account with the same request code doesn\'t exist.'
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
