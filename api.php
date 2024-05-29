<?php
    // CONNECT TO DB 
    $conn = mysqli_connect('localhost','root','','users1');

    if (!$conn) {
        die('Unable to connect to database due to ---> '. mysqli_connect_error());
    }
    

    $response = array();
    $sql = "SELECT * FROM `api_data`";
    $result = mysqli_query($conn, $sql);

    // to display json in readable format
    header("Content-Type: JSON");
    $i=0;
    while ($row = mysqli_fetch_assoc($result)) {
        $response[$i]['id'] = $row['id']; 
        $response[$i]['name'] = $row['name'];
        $response[$i]['age'] = $row['age'];
        $response[$i]['email'] = $row['email'];

        $i++;
    }

    // function to create json json_encode
    echo json_encode($response, JSON_PRETTY_PRINT);

?>