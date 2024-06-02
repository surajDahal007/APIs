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

  if ($requestMethod == 'PUT') {
      $inputData = json_decode(file_get_contents("php://input"),true);

      if (empty($inputData)) {
          $updateData = updateData($_POST, $_GET);
      }
      else {
          $updateData = updateData($inputData, $_GET);
      }
      echo $updateData;
  }
  else {
      $data = [
          'status' => 405,
          'message' => $requestMethod .' Method not allowed.'
      ];
      header("HTTP/1.0 METHOD NOT ALLOWED");
      echo json_encode($data);
  }

  function error4($message){
    $data = [
        'status' => 422,
        'message' => $message
    ];
    header("HTTP/1.0 422 Unable to process given entity");
    echo json_encode($data);
}

  function updateData($inputParam, $updateParam){
    global $conn;

    if (!isset($updateParam['id'])) {
        return error4('Customer ID not found in URL');
    }
    else if($updateParam['id'] == NULL) {
        return error4('Enter the customer id');
    }

    $id = mysqli_real_escape_string($conn, $updateParam['id']);
    $name = mysqli_real_escape_string($conn, $updateParam['name']);
    $age = mysqli_real_escape_string($conn, $updateParam['age']);
    $email = mysqli_real_escape_string($conn, $updateParam['email']);

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
        $sql = "UPDATE `api_data` SET `name` = '$name', `age` = '$age', `email` = '$email' WHERE `api_data`.`id` = '$id'";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            $data = [
                'status' => 200,
                'message' => 'Customer Created Successfully.'
            ];
            header("HTTP/1.0 200 CREATED");
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
?>