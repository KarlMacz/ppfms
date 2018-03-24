<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    $email = input_escape_string($connection, $_POST['email']);

    $query = mysqli_query($connection, "SELECT * FROM `accounts`
        INNER JOIN `users` ON `accounts`.`id`=`users`.`account_id`
        WHERE `accounts`.`email`='$email'");

    if(mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        $changePasswordCode = generate_change_password_code($connection);

        if($row['middle_name'] != null) {
            $fullName = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
        } else {
            $fullName = $row['first_name'] . ' ' . $row['last_name'];
        }

        $accountQuery = mysqli_query($connection, "UPDATE `accounts` SET `change_password_code`='$changePasswordCode' WHERE `email`='$email'");

        if(mysqli_affected_rows($connection) === 1) {
            send_email($email, 'Client Account Registration', '<!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <title>' . COMPANY_NAME . '</title>
                    <style>
                        body { font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif; }
                        h1, h2, h3, h4, h5, h6 { margin 0 0 10px 0; }
                        p { margin: 0; }
                        .content p { margin: 10px 0; }
                    </style>
                </head>
                <body>
                    <h3>Hi ' . $row['first_name'] . ',</h3>
                    <div class="content">
                        <p>It seems that you forgot your account\'s password. To set a new password for your account, visit <a href="' . MY_URL . '/change_password.php?ref=' . $changePasswordCode . '" target="_blank">' . MY_URL . '/change_password.php?ref=' . $changePasswordCode . '</a>.</p>
                        <p>If you did not request for a change of password, please ignore and delete this mail.</p>
                    </div>
                </body>
                </html>', $fullName);

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'A change password link has been sent to your e-mail.',
                'data' => [
                    'url' => 'login.php'
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Failed to make a change password request.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'Error',
            'type' => 'prompt',
            'message' => 'Account registered with this e-mail address doesn\'t exist.'
        ]);
    }

    exit();
?>
