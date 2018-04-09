<?php
    require_once('../database.php');
    require_once('../functions.php');

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inputs = $_POST['inputs'];
        $validations = $_POST['validations'];
        $data = [];
        $count = 0;

        foreach($inputs as $key => $input) {
            if(array_key_exists($input['name'], $validations)) {
                $result = validate_input($input['value'], $validations[$input['name']]);

                $data[] = [
                    'field' => $input['name'],
                    'validation_result' => ($result === true ? true : false),
                    'message' => ($result === true ? '' : $result)
                ];

                if($result !== true) {
                    $count++;
                }
            }
        }

        echo json_encode([
            'status' => 'Ok',
            'type' => 'input',
            'message' => 'Validations complete. ' . $count . ' invalid input(s) found.',
            'invalid_count' => $count,
            'data' => $data
        ]);
    } else {
        echo json_encode([
            'status' => 'Error',
            'type' => 'prompt',
            'message' => 'Unauthorized access.'
        ]);
    }

    exit();
?>
