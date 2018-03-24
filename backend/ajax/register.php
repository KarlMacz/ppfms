<?php
    require_once('../database.php');
    require_once('../functions.php');

    $username = input_escape_string($connection, $_POST['username']);
    $password = input_escape_string($connection, $_POST['password']);
    $confirmPassword = input_escape_string($connection, $_POST['confirm_password']);
    $email = input_escape_string($connection, $_POST['email']);
    $firstName = input_escape_string($connection, $_POST['first_name']);
    $middleName = isset($_POST['middle_name']) && $_POST['middle_name'] !== '' ? input_escape_string($connection, $_POST['middle_name']) : null;
    $lastName = input_escape_string($connection, $_POST['last_name']);
    $gender = input_escape_string($connection, $_POST['gender']);
    $birthDate = input_escape_string($connection, $_POST['birth_date']);
    $address = input_escape_string($connection, $_POST['address']);

    $password = hash('sha256', $password);
    $confirmPassword = hash('sha256', $confirmPassword);
    $verificationCode = generate_verification_code($connection);

    $captchaResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . RECAPTCHA_SECRET_KEY . '&response=' . $_POST['g-recaptcha-response']);
    $captchaResponse = json_decode($captchaResponse);

    if($captchaResponse->success) {
        $query = mysqli_query($connection, "SELECT * FROM `accounts`
            WHERE `username`='$username' OR `email`='$email'");

        if(mysqli_num_rows($query) === 0) {
            if(hash_equals($password, $confirmPassword)) {
                $accountQuery = mysqli_query($connection, "INSERT INTO `accounts` (`username`, `password`, `email`, `verification_code`, `created_at`)
                    VALUES ('$username', '$password', '$email', '$verificationCode', '$today')");

                if(mysqli_affected_rows($connection) === 1) {
                    $accountID = mysqli_insert_id($connection);

                    if($middleName != null) {
                        $userQuery = mysqli_query($connection, "INSERT INTO `users` (`account_id`, `first_name`, `middle_name`, `last_name`, `gender`, `birth_date`, `address`)
                            VALUES ('$accountID', '$firstName', '$middleName', '$lastName', '$gender', '$birthDate', '$address')");
                    } else {
                        $userQuery = mysqli_query($connection, "INSERT INTO `users` (`account_id`, `first_name`, `last_name`, `gender`, `birth_date`, `address`)
                            VALUES ('$accountID', '$firstName', '$lastName', '$gender', '$birthDate', '$address')");
                    }

                    if(mysqli_affected_rows($connection) === 1) {
                        if($middleName != null) {
                            mysqli_query($connection, "INSERT INTO `billing_addresses` (`account_id`, `first_name`, `middle_name`, `last_name`, `billing_address`)
                                VALUES ('$accountID', '$firstName', '$middleName', '$lastName', '$address')");
                            mysqli_query($connection, "INSERT INTO `shipping_addresses` (`account_id`, `first_name`, `middle_name`, `last_name`, `shipping_address`)
                                VALUES ('$accountID', '$firstName', '$middleName', '$lastName', '$address')");
                        } else {
                            mysqli_query($connection, "INSERT INTO `billing_addresses` (`account_id`, `first_name`, `last_name`, `billing_address`)
                                VALUES ('$accountID', '$firstName', '$lastName', '$address')");
                            mysqli_query($connection, "INSERT INTO `shipping_addresses` (`account_id`, `first_name`, `last_name`, `shipping_address`)
                                VALUES ('$accountID', '$firstName', '$lastName', '$address')");
                        }

                        if($middleName != null) {
                            $fullName = $firstName . ' ' . substr($middleName, 0, 1) . '. ' . $lastName;
                        } else {
                            $fullName = $firstName . ' ' . $lastName;
                        }

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
                                <h3>Hi ' . $firstName . ',</h3>
                                <div class="content">
                                    <p>Thank you for registering an account at <a href="' . MY_URL . '" target="_blank">' . COMPANY_NAME . '</a>.</p>
                                    <p>In order to be able to log in your account, we need to verify that it was you who created the said account. To verify, please visit: <a href="' . MY_URL . '/verify_account.php?ref=' . $verificationCode . '" target="_blank">' . MY_URL . '/verify_account.php?code=' . $verificationCode . '</a>.</p>
                                </div>
                            </body>
                            </html>', $fullName);

                        echo json_encode([
                            'status' => 'Ok',
                            'type' => 'prompt',
                            'message' => 'Registration successful.',
                            'data' => [
                                'url' => 'login.php'
                            ]
                        ]);
                    } else {
                        mysqli_query($connection, "DELETE FROM `accounts` WHERE `id`='$accountID'");

                        echo json_encode([
                            'status' => 'Error',
                            'type' => 'prompt',
                            'message' => 'Registration failed.'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'status' => 'Error',
                        'type' => 'prompt',
                        'message' => 'Registration failed.'
                    ]);
                }
            } else {
                echo json_encode([
                    'status' => 'Error',
                    'type' => 'prompt',
                    'message' => 'Password and Confirm Password don\'t match.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'An account the has the same username and/or e-mail address already exist.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'Error',
            'type' => 'prompt',
            'message' => 'reCAPTCHA Failed. Please refresh the page and try again.'
        ]);
    }

    exit();
?>
