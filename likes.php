<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/likesModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        //echo json_encode($data);
        insertLike($data);
        break;

    case 'GET':
        if (isset($_GET['id_post']) && isset($_GET['email']))
            searchLikedPost($_GET['id_post'], $_GET['email']);
        break;

    case 'PUT':

        break;

    case 'DELETE':
        if (isset($_GET['id_post']) && isset($_GET['email']))
            deleteLike($_GET['id_post'], $_GET['email']);

        break;
}

function insertLike($data)
{
    $database = new Database();
    $db = $database->connect();
    $like = new LikedPublication($db);
    $like->email = $data->email;
    $like->id_publication = $data->id_publication;

    $err = $like->insertLike();
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

function deleteLike($id, $email)
{
    $database = new Database();
    $db = $database->connect();
    $like = new LikedPublication($db);
    $like->email = $email;
    $like->id_publication = $id;

    $err = $like->deleteLike();
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

function searchLikedPost($id, $email)
{
    $database = new Database();
    $db = $database->connect();
    $like = new LikedPublication($db);
    $like->id_publication = $id;
    $like->email = $email;
    $result = $like->searchLike();
    echo $result;
}
