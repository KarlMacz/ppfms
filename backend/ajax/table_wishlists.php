<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userID = $_SESSION['user_id'];

        $query = mysqli_query($connection, "SELECT * FROM `wishlists`
            INNER JOIN `products`
                ON `wishlists`.`product_id`=`products`.`id`
            WHERE `wishlists`.`account_id`='$userID'");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $wishlist = [];

            $query = mysqli_query($connection, "SELECT *,
                    `wishlists`.`id` AS `wishlist_id`
                FROM `wishlists`
                INNER JOIN `products`
                    ON `wishlists`.`product_id`=`products`.`id`
                WHERE `wishlists`.`account_id`='$userID'
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $wishlist[] = [
                    'product_code' => $row['product_code'],
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'type' => $row['type'],
                    'actions' => '<button type="button" class="remove-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Remove Product from Wishlist" data-id="' . $row['wishlist_id'] . '"><span class="fas fa-times fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' product(s) retrieved.',
                'data_total_count' => $count,
                'data' => $wishlist
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
