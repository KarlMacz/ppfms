<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $query = mysqli_query($connection, "SELECT * FROM `suppliers`");
        $count = mysqli_num_rows($query);

        if($count > 0) {
            $start = (int) input_escape_string($connection, $_POST['start']);
            $limit = (int) input_escape_string($connection, $_POST['limit']);
            $suppliers = [];

            $query = mysqli_query($connection, "SELECT * FROM `suppliers` LIMIT $start, $limit");

            while($row = mysqli_fetch_assoc($query)) {
                $suppliers[] = [
                    'name' => $row['name'],
                    'actions' => '<button type="button" class="view-supplier-button btn btn-primary btn-xs" data-toggle="tooltip" data-placement="top" title="View Supplier\'s Information" data-id="' . $row['id'] . '"><span class="fas fa-bars fa-fw"></span></button>
                        <button type="button" class="delete-supplier-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete Supplier\'s Information" data-id="' . $row['id'] . '"><span class="fas fa-trash fa-fw"></span></button>'
                ];
            }

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => $count . ' supplier(s) retrieved.',
                'data_total_count' => $count,
                'data' => $suppliers
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
