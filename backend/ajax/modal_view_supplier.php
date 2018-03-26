<?php
    session_start();

    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $id = input_escape_string($connection, $_POST['id']);

        $query = mysqli_query($connection, "SELECT * FROM `suppliers` WHERE `id`='$id'");

        if(mysqli_num_rows($query) === 1) {
            $row = mysqli_fetch_assoc($query);

            $outputBody = '<div class="text-center">
                    ' . ($row['logo'] != null ? '<img src="../img/' . $row['logo'] . '">' : '<div class="alert alert-info">No logo preview.</div>') . '
                </div>
                <h2>' . $row['name'] . '</h2>
                <p>' . nl2br($row['description']) . '</p>';

            echo json_encode([
                'status' => 'Ok',
                'type' => 'prompt',
                'message' => 'Supplier\'s information has been retrieved.',
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
