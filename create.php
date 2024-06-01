<?php
    // CONNECT TO DB 
    $conn = mysqli_connect('localhost','root','','users1');

    if (!$conn) {
        die('Unable to connect to database due to ---> '. mysqli_connect_error());
    }

    // Turn off error reporting
    error_reporting(0);

    // headers here
    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin:*');
    header('Access-Method-Allow-Method: POST');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod == 'POST') {
        $inputData = json_decode(file_get_contents("php://input"),true);

        if (empty($inputData)) {
            $storeData = storeData($_POST);
        }
        else {
            $storeData = storeData($inputData);
        }
        echo $storeData;
    }
    else {
        $data = [
            'status' => 405,
            'message' => $requestMethod .' Method not allowed.'
        ];
        header("HTTP/1.0 METHOD NOT ALLOWED");
        echo json_encode($data);
    }


    function storeData($dataInput)
    {
        global $conn;

        $name = mysqli_real_escape_string($conn, $dataInput['name']);
        $age = mysqli_real_escape_string($conn, $dataInput['age']);
        $email = mysqli_real_escape_string($conn, $dataInput['email']);

        if (empty(trim($name))) {
            return error4('Enter your name');
        }
        elseif (empty(trim($age))) {
            return error4('Enter your age');
        }
        elseif (empty(trim($email))) {
            return error4('Enter your email');
        }
        else {
            $sql = "INSERT INTO `api_data` (`name`, `age`, `email`) VALUES ('$name', '$age', '$email')";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                $data = [
                    'status' => 201,
                    'message' => 'NEW RECORD CREATED'
                ];
                header("HTTP/1.0 201 CREATED");
                return json_encode($data);
            }
            else {
                $data = [
                    'status' => 500,
                    'message' => 'INTERNAL SERVER ERROR'
                ];
                header("HTTP/1.0 500 INTERNAL SERVER ERROR");
                return json_encode($data);
            }
        }
    }

    function error4($message){
        $data = [
            'status' => 422,
            'message' => $message
        ];
        header("HTTP/1.0 422 Unable to process given entity");
        echo json_encode($data);
    }

?>