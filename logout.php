<?php
    require_once('backend/database.php');

    session_start();

    $accountID = $_SESSION['user_id'];

    mysqli_query($connection, "INSERT INTO `logs` (`acccount_id`, `message`, `created_at`) VALUES ('$accountID', 'has logged out.', '$today')");

    session_destroy();

    header('Location: login.php');
?>
