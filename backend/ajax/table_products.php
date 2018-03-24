<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $query = mysqli_query($connection, "SELECT * FROM `products`");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $products = [];

            $query = mysqli_query($connection, "SELECT * FROM `products` LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $products[] = [
                    'name' => $row['name'],
                    'category' => $row['category'],
                    'type' => $row['type'],
                    'actions' => '<button type="button" class="view-button btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="View Product Information" data-id="' . $row['id'] . '" data-name="' . $row['name'] . '" data-available="' . $row['quantity_available'] . '"><span class="fas fa-bars fa-fw"></span></button>'
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
