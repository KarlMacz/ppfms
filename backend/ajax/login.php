<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    $username = input_escape_string($connection, $_POST['username']);
    $password = input_escape_string($connection, $_POST['password']);
    $password = hash('sha256', $password);

    $query = mysqli_query($connection, "SELECT *, `accounts`.`id` AS `account_id` FROM `accounts`
        INNER JOIN `users` ON `accounts`.`id`=`users`.`account_id`
        WHERE `accounts`.`username`='$username'
            AND `accounts`.`password`='$password'");

    if(mysqli_num_rows($query) === 1) {
        $row = mysqli_fetch_assoc($query);

        if($row['is_verified']) {
            $accountID = $row['account_id'];
            $_SESSION['user_id'] = $accountID;
            $_SESSION['username'] = $row['username'];
            $_SESSION['type'] = $row['type'];
            
            if($row['middle_name'] != null) {
                $_SESSION['full_name'] = $row['first_name'] . ' ' . substr($row['middle_name'], 0, 1) . '. ' . $row['last_name'];
            } else {
                $_SESSION['full_name'] = $row['first_name'] . ' ' . $row['last_name'];
            }

            if($row['type'] === 'Client') {
                $url = 'product_purchasing/index.php';
            } else {
                $url = 'factory_management/index.php';
            }

            mysqli_query($connection, "INSERT INTO `logs` (`acccount_id`, `message`, `created_at`) VALUES ('$accountID', 'has logged in.', '$today')");

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Login successful.',
                'data' => [
                    'url' => $url
                ]
            ]);
        } else {
            echo json_encode([
                'status' => 'Error',
                'type' => 'prompt',
                'message' => 'Login Failed. Your account is not yet verified. Please check your e-mail for verification link.'
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'Error',
            'type' => 'prompt',
            'message' => 'Invalid username and/or password.'
        ]);
    }

    exit();
?>
