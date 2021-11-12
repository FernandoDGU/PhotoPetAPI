<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/albumModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        //$data = json_decode(file_get_contents("php://input"));

        break;

    case 'GET':
        if (isset($_GET['id_post']))
            getAlbumsPost($_GET['id_post']);
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

function getAlbumsPost($id)
{
    $database = new Database();
    $db = $database->connect();
    $album = new Album($db);
    $result = $album->getAlbumsPost($id);
    $num = $result->rowCount();

    if ($num > 0) {
        $albums_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $album_item = array(
                'id_album' => $id_album,
                'id_publication'    => $id_publication,
                'description'          => $description,
                'imageString'       => "data:image/png;base64," . base64_encode($image)
            );

            //array_push($users_arr['data'], $user_item);
            array_push($albums_arr, $album_item);
        }

        echo json_encode($albums_arr);
    } else {
        $albums_arr = array();
        $album_item = array(
            'id_album' => null,
            'id_publication'    => null,
            'description'          => null,
            'imageString'       => null

        );
        array_push($albums_arr, $album_item);
        echo json_encode($albums_arr);
    }
}
