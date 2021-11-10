<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/tagModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        insertTag($data);
        break;

    case 'GET':
        getTags();
        break;

    case 'PUT':
        if (isset($_GET['id'])) {
            $data = json_decode(file_get_contents("php://input"));
            //updateUser($data, $_GET['email']);
        }
        break;

    case 'DELETE':
        if (isset($_GET['email'])) {
            //deleteUser($_GET['email']);
        }
        break;
}

function insertTag($data)
{
    $database = new Database();
    $db = $database->connect();
    $tag = new Tag($db);
    $tag->tagname = $data->tagname;


    $err = $tag->insertTag();
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

function getTags()
{
    $database = new Database();
    $db = $database->connect();
    $tag = new Tag($db);
    $result = $tag->read();
    $num = $result->rowCount();

    if ($num > 0) {

        //$users_arr['data'] = array();

        $tags_arr = array();
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $tag_item = array(
                'id_tag' => $id_tag,
                'tagname' => $tagname
            );

            //array_push($users_arr['data'], $user_item);
            array_push($tags_arr, $tag_item);
        }

        echo json_encode($tags_arr);
    } else {
        $tags_arr = array();
        $tag_item = array(
            'id_tag' => null,
            'tagname' => null
        );
        array_push($tags_arr, $tag_item);

        echo json_encode($tags_arr);
    }
}
