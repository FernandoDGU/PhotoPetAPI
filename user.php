<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/userModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        insertUser($data);
        break;

    case 'GET':
        if (isset($_GET['email']) && isset($_GET['pass']))
            logInUser($_GET['email'], $_GET['pass']);
        else if (isset($_GET['email']))
            getUserByEmail($_GET['email']);
        else
            getUsers();
        break;

    case 'PUT':
        if (isset($_GET['email'])) {
            $data = json_decode(file_get_contents("php://input"));
            updateUser($data, $_GET['email']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['email'])) {
            deleteUser($_GET['email']);
        }
        break;
}

function getUsers()
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $users_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $user_item = array(
                'email'         => $email,
                'fullname'          => $fullname,
                'firstname'          => $firstname,
                'lastname'          => $lastname,
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
        $user_arr = array(
            'email'         => null,
            'fullname'          => null,
            'firstname'          => null,
            'lastname'          => null,
            'password'      => null,
            'phone'         => null,
            'description'   => null,
            'image'         => null
        );
        echo json_encode($user_arr);
    }
}

function getUserByEmail($id)
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->singleRead($id);
    if (empty($user->email)) {
        $user_arr = array(
            'email'         => null,
            'fullname'      => null,
            'firstname'     => null,
            'lastname'      => null,
            'password'      => null,
            'phone'         => null,
            'description'   => null,
            'image'         => null
        );
        echo json_encode($user_arr);
    } else {
        $user_arr = array(
            'email'         => $user->email,
            'fullname'      => $user->fullname,
            'firstname'     => $user->firstname,
            'lastname'      => $user->lastname,
            'password'      => $user->password,
            'phone'         => $user->phone,
            'description'   => $user->description,
            'image'         => $user->image
        );
        echo json_encode($user_arr);
    }
}

function logInUser($email, $pass)
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->userLogged($email, $pass);
    if (empty($user->email)) {
        $user_arr = array(
            'email'         => null,
            'fullname'      => null,
            'firstname'     => null,
            'lastname'      => null,
            'password'      => null,
            'phone'         => null,
            'description'   => null,
            'image'         => null
        );
        echo json_encode($user_arr);
    } else {
        $user_arr = array(
            'email'         => $user->email,
            'fullname'      => $user->fullname,
            'firstname'     => $user->firstname,
            'lastname'      => $user->lastname,
            'password'      => $user->password,
            'phone'         => $user->phone,
            'description'   => $user->description,
            'image'         => $user->image
        );
        echo json_encode($user_arr);
    }
}

function insertUser($data)
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $data->email;
    $user->fullname = $data->fullname;
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->password = $data->password;
    $user->phone = $data->phone;
    $user->description = $data->description;
    $user->image = $data->image;

    $err = $user->insertUser();
    //echo $err;

    if ($err == "ok") {
        echo json_encode(
            array('message' => 'ok')
        );
    } else {
        echo json_encode(
            array('message' => $err)
        );
    }
}

function updateUser($data, $filterEmail)
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $filterEmail;
    $user->fullname = $data->fullname;
    $user->firstname = $data->firstname;
    $user->lastname = $data->lastname;
    $user->password = $data->password;
    $user->phone = $data->phone;
    $user->description = $data->description;
    $user->image = $data->image;

    if ($user->updateUser()) {
        echo json_encode(
            array('message' => 'ok')
        );
    } else {
        echo json_encode(
            array('message' => '0')
        );
    }
}

function deleteUser($filterEmail)
{
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $user->email = $filterEmail;

    if ($user->deleteUser()) {
        echo json_encode(
            array('message' => 'User Eliminated')
        );
    } else {
        echo json_encode(
            array('message' => 'User Not Eliminated')
        );
    }
}
