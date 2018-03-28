<?php
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');

        exit();
    } else {
        if($_SESSION['type'] !== 'Administrator') {
            header('Location: ../login.php');

            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo COMPANY_NAME; ?></title>
    <link rel="shortcut icon" href="../img/logo.png">
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/metisMenu.min.css">
    <link rel="stylesheet" href="../css/sb-admin-2.min.css">
    <!-- <link rel="stylesheet" href="../css/jquery.dataTables.min.css"> -->
    <link rel="stylesheet" href="../css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="../css/stylesheet.css">
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/metisMenu.min.js"></script>
    <script src="../js/sb-admin-2.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/dataTables.bootstrap.min.js"></script>
    <script src="../js/script.js"></script>
</head>
<body>
