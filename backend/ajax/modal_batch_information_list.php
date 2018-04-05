<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `batch_information`
            WHERE `batch_id`='$id'");

        if(mysqli_num_rows($query) > 0) {
            $outputBody = '<table class="table table-bordered table-striped no-margin">
                <thead>
                    <tr>
                        <th>Additional Information</th>
                        <th width="15%">Action(s)</th>
                    </tr>
                </thead>
                <tbody>';

            while($row = mysqli_fetch_assoc($query)) {
                $outputBody .= '<tr>
                        <td>' . $row['additional_information'] . '</td>
                        <td class="text-center">
                            <button type="button" class="remove-extra-button btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Remove Additional Information" data-id="' . $row['id'] . '"><span class="fas fa-times fa-fw"></span></button>
                        </td>
                    </tr>';
            }

            $outputBody .= '</tbody>
                </table>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Batch information has been retrieved.',
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
