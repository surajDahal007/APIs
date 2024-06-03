<?php
    // CONNECT TO DB 
    $conn = mysqli_connect('localhost','root','','users1');

    if (!$conn) {
        die('Unable to connect to database due to ---> '. mysqli_connect_error());
    }

    header("Content-Type: application/json");
    header('Access-Control-Allow-Origin:*');
    header('Access-Method-Allow-Method: DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');

    $requestMethod = $_SERVER['REQUEST_METHOD'];

    if ($requestMethod == 'DELETE') {

        $deleteData = deleteData($_GET);
        echo $deleteData;
        
    }
    else {
        $data = [
            'status' => 405,
            'message' => $requestMethod .' Method not allowed.'
        ];
        header("HTTP/1.0 METHOD NOT ALLOWED");
        echo json_encode($data);
    }

    function deleteData($params){
        global $conn;

        if (!isset($params['id'])) {
            return error4('ID not found');
        } elseif($params['id'] == NULL) {
            return error4('Enter ID');
        }

        $id = mysqli_real_escape_string($conn, $params['id']);

        $sql = "DELETE FROM `api_data` WHERE `api_data`.`id` = '$id' LIMIT 1";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = [
                'status' => 204,
                'message' => 'SUCCESS'
            ];
        header("HTTP/1.0 204 SUCCESS");
        return json_encode($data);

        }else {
            $data = [
                    'status' => 404,
                    'message' => 'CUSTOMER NOT FOUND'
                ];

            header("HTTP/1.0 404 CUSTOMER NOT FOUND");
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


    // function getList(){
    //     global $conn,$requestMethod;

    //     $sql = 'SELECT * FROM `api_data`';
    //     $result = mysqli_query($conn, $sql);

    //    if ($result) 
    //    {
    //         if (mysqli_num_rows($result)>0) {
    //             $response = mysqli_fetch_all($result, MYSQLI_ASSOC);

    //             // RETURN SUCCESS RESPONSE
    //             $data = [
    //                 'status' => 200,
    //                 'message' => $requestMethod .' API DATA FETCHED',
    //                 'data' => $response
    //             ];
                
    //             header("HTTP/1.0 200 OK");
    //             return json_encode($data);
    //         }
    //         else {
    //             $data = [
    //                 'status' => 404,
    //                 'message' => $requestMethod .' No record found'
    //             ];
    //             header("HTTP/1.0 404 No record found");
    //             return json_encode($data);
    //         }
    //    } 
    //    else 
    //    {
    //         $data = [
    //             'status' => 500,
    //             'message' => $requestMethod .' Method not allowed.'
    //         ];
    //         header("HTTP/1.0 500 INTERNAL SERVER ERROR");
    //         return json_encode($data);
    //    }
    // } 
?>