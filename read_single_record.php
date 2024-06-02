<?php
    // CONNECT TO DB 
    $conn = mysqli_connect('localhost','root','','users1');

    if (!$conn) {
        die('Unable to connect to database due to ---> '. mysqli_connect_error());
    }

    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin:*');
    header('Access-Method-Allow-Method: GET');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod == 'GET') {

        if (isset($_GET['id'])) {
            $apiData = getSingleData($_GET);
            echo $apiData;
        }
        else {
            $list = getList();
            echo $list;    
        }
    }
    else {
        $data = [
            'status' => 405,
            'message' => $requestMethod .' Method not allowed.'
        ];
        header("HTTP/1.0 METHOD NOT ALLOWED");
        echo json_encode($data);
    }


    function getList(){
        global $conn, $requestMethod;

        $sql = 'SELECT * FROM `api_data`';
        $result = mysqli_query($conn, $sql);

       if ($result) 
       {
            if (mysqli_num_rows($result)>0) {
                $response = mysqli_fetch_all($result, MYSQLI_ASSOC);

                // RETURN SUCCESS RESPONSE
                $data = [
                    'status' => 200,
                    'message' => $requestMethod .' API DATA FETCHED',
                    'data' => $response
                ];
                
                header("HTTP/1.0 200 OK");
                return json_encode($data);
            }
            else {
                $data = [
                    'status' => 404,
                    'message' => $requestMethod .' No record found'
                ];
                header("HTTP/1.0 404 No record found");
                return json_encode($data);
            }
       } 
       else 
       {
            $data = [
                'status' => 500,
                'message' => $requestMethod .' Method not allowed.'
            ];
            header("HTTP/1.0 500 INTERNAL SERVER ERROR");
            return json_encode($data);
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

    function getSingleData($parameters){
        global $conn;

        if ($parameters['id'] == NULL) {
            return error4('Enter id');
        }

        $id = mysqli_real_escape_string($conn, $parameters['id']);
        $sql = "SELECT * FROM `api_data` WHERE id = '$id'";

        $result = mysqli_query($conn, $sql);

        if ($result) {

            if (mysqli_num_rows($result) == 1) {

                $response = mysqli_fetch_assoc($result);
                $data = [
                    'status' => 200,
                    'message' =>'Data Fetched Successfully.',
                    'data' => $response
                ];
                header("HTTP/1.0 200 OK");
                return json_encode($data);

            }
            else {
                $data = [
                    'status' => 404,
                    'message' =>'No customer found.'
                ];
                header("HTTP/1.0 500 NOT FOUND");
                return json_encode($data);
            }

        } else {
            $data = [
                'status' => 500,
                'message' => $requestMethod .' Method not allowed.'
            ];
            header("HTTP/1.0 500 INTERNAL SERVER ERROR");
            return json_encode($data);
        }
        
      
    }
?>