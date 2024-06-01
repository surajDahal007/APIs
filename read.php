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
        $list = getList();
        echo $list;
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
        global $conn,$requestMethod;

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
?>