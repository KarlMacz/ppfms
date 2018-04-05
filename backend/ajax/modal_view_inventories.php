<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `products`
            WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $outputBody = '<h2 class="no-margin">' . $row['name'] . '</h2>
                <div style="margin-bottom: 10px;">' . $row['description'] . '</div>';

            $query = mysqli_query($connection, "SELECT *, `inventories`.`id` AS `inventory_id` FROM `inventories`
                LEFT JOIN `suppliers`
                    ON `inventories`.`supplier_id`=`suppliers`.`id`
                WHERE `inventories`.`product_id`='$id'");

            $outputBody .= '<div style="overflow-y: scroll; max-height: 200px;">
                <table class="table table-bordered table-striped no-margin">
                    <thead>
                        <tr>
                            <th>Supplier</th>
                            <th width="15%">Date Ordered</th>
                            <th width="15%">Boxes Arrived</th>
                            <th width="15%">Boxes Remaining</th>
                            <th width="15%">Excess Boxes</th>
                            <th width="15%">Action(s)</th>
                        </tr>
                    </thead>
                    <tbody>';

            if(mysqli_num_rows($query) > 0) {
                while($row = mysqli_fetch_assoc($query)) {
                    $outputBody .= '<tr>
                        <td>' . $row['name'] . '</td>
                        <td>' . date('F d, Y', strtotime($row['date_ordered'])) . '</td>
                        <td class="text-center">' . $row['boxes_arrived'] . '</td>
                        <td class="text-center">' . $row['boxes_in_stock'] . '</td>
                        <td class="text-center">' . $row['excess_boxes'] . '</td>
                        <td class="text-center">';

                    if($row['boxes_in_stock'] > 0) {
                        $outputBody .= '<button type="button" class="fetch-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Fetch Box" data-id="' . $row['inventory_id'] . '"><span class="fas fa-level-up-alt fa-fw"></span></button>
                            <button type="button" class="excess-button btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Excess Material Registry" data-id="' . $row['inventory_id'] . '" data-in-stock="' . $row['boxes_in_stock'] . '"><span class="fas fa-level-down-alt fa-fw"></span></button>
                            <button type="button" class="issue-button btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Issue Registry" data-id="' . $row['inventory_id'] . '"><span class="fas fa-question fa-fw"></span></button>';
                    } else {
                        $outputBody .= '<button type="button" class="fetch-button btn btn-success btn-xs" data-toggle="tooltip" data-placement="top" title="Fetch Box" data-id="' . $row['inventory_id'] . '"><span class="fas fa-level-up-alt fa-fw"></span></button>
                            <button type="button" class="issue-button btn btn-warning btn-xs" data-toggle="tooltip" data-placement="top" title="Issue Registry" data-id="' . $row['inventory_id'] . '"><span class="fas fa-question fa-fw"></span></button>';
                    }

                    $outputBody .= '</td>
                        </tr>';
                }
            } else {
                $outputBody .= '<tr>
                        <td class="text-center" colspan="6">No stocks left.</td>
                    </tr>';
            }

            $outputBody .= '</tbody>
                    </table>
                </div>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Product Inventory has been retrieved.',
                'output' => [
                    'body' => $outputBody
                ]
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
