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

            $query = mysqli_query($connection, "SELECT * FROM `products`
                LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $productID = $row['id'];

                $query2 = mysqli_query($connection, "SELECT `boxes_in_stock` FROM `inventories`
                    WHERE `product_id`='$productID'");

                $stocks = 0;

                while($row2 = mysqli_fetch_assoc($query2)) {
                    $stocks += $row2['boxes_in_stock'];
                }

                $products[] = [
                    'code' => $row['product_code'],
                    'name' => $row['name'],
                    'description' => $row['description'],
                    'stocks' => $stocks . ($stocks > 1 ? ' boxes' : ' box'),
                    'actions' => '<button type="button" class="view-button btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="View Stocks" data-id="' . $row['id'] . '"><span class="fas fa-bars fa-fw"></span></button>
                        <button type="button" class="print-qr-button btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Print QR Code" data-id="' . $row['id'] . '"><span class="fas fa-print fa-fw"></span></button>'
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
