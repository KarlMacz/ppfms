<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT *,
                `products`.`name` AS `product_name`,
                `products`.`description` AS `product_description`,
                `suppliers`.`name` AS `supplier_name`,
                `suppliers`.`description` AS `supplier_description`
            FROM `inventories`
            INNER JOIN `products`
                ON `inventories`.`product_id`=`products`.`id`
            INNER JOIN `suppliers`
                ON `inventories`.`supplier_id`=`suppliers`.`id`
            WHERE `inventories`.`id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $outputBody = '<h2 class="no-margin">' . $row['product_name'] . '</h2>
                <div style="margin-bottom: 10px;">Ordered on <strong>' . date('F d, Y', strtotime($row['date_ordered'])) . '</strong>. Supplied by <strong>' . $row['supplier_name'] . '</strong>.</div>
                <div style="overflow-y: scroll; max-height: 200px;">
                    <table class="table table-bordered table-striped no-margin">
                        <thead>
                            <tr>
                                <th>Issue</th>
                                <th width="15%">Action(s)</th>
                            </tr>
                        </thead>
                        <tbody>';

            $issuesQuery = mysqli_query($connection, "SELECT * FROM `issues` WHERE `inventory_id`='$id'");

            if(mysqli_num_rows($issuesQuery) > 0) {
                while($issuesRow = mysqli_fetch_assoc($issuesQuery)) {
                    $outputBody .= '<tr>
                            <td>' . $issuesRow['issue'] . '</td>
                            <td class="text-center">
                                <button type="button" class="remove-issue-button btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove Issue" data-id="' . $issuesRow['id'] . '"><span class="fas fa-times fa-fw"></span></button>
                            </td>
                        </tr>';
                }
            } else {
                $outputBody .= '<tr>
                        <td class="text-center" colspan="2">No issues found.</td>
                    </tr>';
            }

            $outputBody .= '</tbody>
                    </table>
                </div>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Issue List has been retrieved.',
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
