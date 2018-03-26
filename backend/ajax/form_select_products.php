<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $query = mysqli_query($connection, "SELECT * FROM `products`");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            while($row = mysqli_fetch_assoc($query)) {
                $products[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' product(s) retrieved.',
                'data_total_count' => $count,
                'data' => $products
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
