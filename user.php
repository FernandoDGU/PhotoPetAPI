<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/userModel.php';

 switch($_SERVER['REQUEST_METHOD']){

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        insertUser($data);
        break;

    case 'GET':
        if(isset($_GET['email']) && isset($_GET['pass']))
            logInUser($_GET['email'], $_GET['pass']);
        else if(isset($_GET['email']))
            getUserByEmail($_GET['email']);
        else
            getUsers();
        break;
    
    case 'PUT':
        if(isset($_GET['email']))
        {
            $data = json_decode(file_get_contents("php://input"));
            updateUser($data, $_GET['email']);
        }
        break;

    case 'DELETE':
        if(isset($_GET['email']))
        {
            deleteUser($_GET['email']);
        }
        break;

        

 }

function getUsers(){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->read();
    $num = $result->rowCount();
   
    if($num > 0){
       $users_arr = array();
       //$users_arr['data'] = array();
   
       while($row = $result->fetch(PDO::FETCH_ASSOC)){
           extract($row);
   
           $user_item = array(
               'email'         => $email,
               'name'          => $name,
               'password'      => $password,
               'phone'         => $phone,
               'description'   => $description,
               'image'         => "data:image/png;base64," . base64_encode($image)
           );
   
           //array_push($users_arr['data'], $user_item);
           array_push($users_arr, $user_item);
       }
   
       echo json_encode($users_arr);
   
    } else {
       echo json_encode(
           array('message' => 'No Users Found')
       );
    }
}

function getUserByEmail($id){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->singleRead($id);
    if(empty($user->email)){
        echo json_encode(
            array('message' => 'No User Found')
        );
    }else{
        $user_arr = array(
            'email'         => $user->email,
            'name'          => $user->name,
            'password'      => $user->password,
            'phone'         => $user->phone,
            'description'   => $user->description,
            'image'         => $user->image
        );
        echo json_encode($user_arr);
    }
    
    
}

function logInUser($email, $pass){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->userLogged($email, $pass);
    if(empty($user->email)){
        echo json_encode(
            array('message' => 'No User Found')
        );
    }else{
        $user_arr = array(
            'email'         => $user->email,
            'name'          => $user->name,
            'password'      => $user->password,
            'phone'         => $user->phone,
            'description'   => $user->description,
            'image'         => $user->image
        );
        echo json_encode($user_arr);
    }
}

function insertUser($data){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $data->email;
    $user->name = $data->name;
    $user->password = $data->password;
    $user->phone = $data->phone;
    $user->description = $data->description;
    $user->image = $data->image;

    $err = $user->insertUser();
    //echo $err;
    
    if($err == "ok"){
        echo json_encode(
            array('message' => 'ok')
        );
    }else{
        echo json_encode(
            array('message' => $err)
        );
    }
}

function updateUser($data, $filterEmail){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $filterEmail;
    $user->name = $data->name;
    $user->password = $data->password;
    $user->phone = $data->phone;
    $user->description = $data->description;
    $user->image = $data->image;

    if($user->updateUser()){
        echo json_encode(
            array('message' => 'User Modificated')
        );
    }else{
        echo json_encode(
            array('message' => 'User Not Modificated')
        );
    }
}

function deleteUser($filterEmail){
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $filterEmail;

    if($user->deleteUser()){
        echo json_encode(
            array('message' => 'User Eliminated')
        );
    }else{
        echo json_encode(
            array('message' => 'User Not Eliminated')
        );
    }
}