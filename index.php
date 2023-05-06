<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    require('DB.php');


    $method = $_SERVER['REQUEST_METHOD'];

    if($method == "GET"){
        $sql = "SELECT * FROM tblcustomer";
        if(isset($_GET['id'])){
            $sql = "SELECT * FROM tblcustomer WHERE id =" . $_GET['id'];
        }

        $db = new DB();
        //Connect Database
        $connect = $db->connect();
        //Execute Query
        $result = mysqli_query($connect, $sql);
    
        //Check number of row
        if (mysqli_num_rows($result) > 0) {
            // Fetch each data (mysqli_fetch_all get assosiative and value array)
            while($row = mysqli_fetch_all($result, MYSQLI_ASSOC)) {
                $data = $row;
            }
        } 
        else {
            $data = "0 results";
        }
        mysqli_free_result($result);
        $db->closeConnection($connect);
        echo json_encode($data);
    }

    if($method == "POST"){
        $data = urldecode(file_get_contents('php://input'));
        
        $value = json_decode($data, TRUE);

        $db = new DB();
        $sql = "INSERT INTO tblcustomer (first_name, last_name, add_ress) VALUES (?,?,?)";
    
        //Connect Database
        $connect = $db->connect();
        //Execute Query
        if($stmt = mysqli_prepare($connect, $sql)){
            mysqli_stmt_bind_param($stmt, "sss", $first_name, $last_name, $add_ress);
            
            $first_name = $value['first_name'];  
            $last_name = $value['last_name']; 
            $add_ress =  $value['add_ress'];  
            mysqli_stmt_execute($stmt);
        }
        else{
            echo json_decode("No Record Found");
        }
        
        mysqli_stmt_close($stmt);
        $db->closeConnection($connect);
        $response =
        [
            "Message" => "Record Added Successful",
        ];
        echo json_encode($response);
    }

    if($method == "PUT"){
        $message = null;
        $sql = null;

        $data = urldecode(file_get_contents('php://input'));
        
        $value = json_decode($data, TRUE);

        if(isset($_GET['id'])){
            $first_name = $value['first_name'];  
            $last_name = $value['last_name']; 
            $add_ress =  $value['add_ress'];  
            $sql = "UPDATE tblcustomer SET first_name = '$first_name', last_name = '$last_name', add_ress = '$add_ress' WHERE id = " . $_GET['id'];
        }
        else{
            die("Error ID");
        }

        $db = new DB();
        //Connect Database
        $connect = $db->connect();
        //Execute Query
        
        if (mysqli_query($connect, $sql)) {
            $message = "Record Update Successful";
        } 
        else {
            $message = "Error Updating record";
        }
        $db->closeConnection($connect);
        echo json_encode($message);      
    }

    if($method == "DELETE"){
        $message = null;
        $sql = null;

        $data = urldecode(file_get_contents('php://input'));
        
        $value = json_decode($data, TRUE);

        if(isset($_GET['id'])){
            $sql = "DELETE FROM tblcustomer WHERE id = " . $_GET['id'];
        }
        else{
            die("Error ID");
        }

        $db = new DB();
        //Connect Database
        $connect = $db->connect();
        //Execute Query
        
        if (mysqli_query($connect, $sql)) {
            $message = "Record Delete Successful";
        } 
        else {
            $message = "Error Updating record";
        }
        $db->closeConnection($connect);
        echo json_encode($message);      
    }


?>