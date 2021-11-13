<?php
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/publicationModel.php';
include_once 'models/albumModel.php';
include_once 'models/tagModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        insertPost($data);
        break;

    case 'GET':
        if (isset($_GET['id_tag']))
            getTagPosts($_GET['id_tag']);
        else if (isset($_GET['id']))
            getPostById($_GET['id']);
        else if (isset($_GET['email']) && !isset($_GET['likes']))
            getUserPosts($_GET['email']);
        else if (isset($_GET['email']) && isset($_GET['likes']))
            getUserLikedPosts($_GET['email']);
        else
            getPosts();
        break;

    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        updatePost($data);
        break;

    case 'DELETE':
        if (isset($_GET['email'])) {
            //deleteUser($_GET['email']);
        }
        break;
}

function insertPost($data)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $post->description = $data->description;
    $post->email = $data->email;


    $err = $post->insertPost();
    //echo $err;
    $db = null;
    if ($err == "ok") {
        foreach ($data->albums as $album) {
            $db = $database->connect();
            $albumObject = new Album($db);
            $albumObject->id_publication = $post->id_publication;
            $albumObject->image = $album->imageString;
            $albumObject->description = $album->description;
            $err = $albumObject->insertAlbum();
            $db = null;
            $albumObject = null;
            if ($err != "ok") {
                echo json_encode(
                    array('message' => "Error insertando el album")
                );
                break;
            }
        }
        foreach ($data->tags as $tag) {
            $db = $database->connect();
            $tagObject = new Tag($db);
            $tagObject->id_publication = $post->id_publication;
            $tagObject->id_tag = $tag->id_tag;
            $err = $tagObject->insertTagPublication();
            $db = null;
            $albumObject = null;
            if ($err != "ok") {
                echo json_encode(
                    array('message' => "Error insertando etiqueta-publicación")
                );
                break;
            }
        }
        if ($err == "ok") {
            echo json_encode(
                array('message' => "ok")
            );
        }
    } else {
        echo json_encode(
            array('message' => "Error insertando la publicación")
        );
    }
}

function updatePost($data)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $post->description = $data->description;
    $post->id_publication = $data->id_publication;
    $post->email = $data->email;

    $err = $post->updatePost();
    $db = null;
    if ($err == "ok") {
        foreach ($data->albums as $album) {
            $db = $database->connect();
            $albumObject = new Album($db);
            $albumObject->id_publication = $data->id_publication;
            $albumObject->image = $album->imageString;
            $albumObject->description = $album->description;
            $err = $albumObject->insertAlbum();
            $db = null;
            $albumObject = null;
            if ($err != "ok") {
                echo json_encode(
                    array('message' => "Error insertando el album")
                );
                break;
            }
        }
        foreach ($data->tags as $tag) {
            $db = $database->connect();
            $tagObject = new Tag($db);
            $tagObject->id_publication = $data->id_publication;
            $tagObject->id_tag = $tag->id_tag;
            $err = $tagObject->insertTagPublication();
            $db = null;
            $albumObject = null;
            if ($err != "ok") {
                echo json_encode(
                    array('message' => "Error insertando etiqueta-publicación")
                );
                break;
            }
        }
        if ($err == "ok") {
            echo json_encode(
                array('message' => "ok")
            );
        }
    } else {
        echo json_encode(
            array('message' => "Error insertando la publicación")
        );
    }
}

function getTagPosts($id)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $result = $post->readTagPosts($id);
    $num = $result->rowCount();

    if ($num > 0) {
        $posts_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id_publication' => $id_publication,
                'description'    => $description,
                'email'          => $email,
                'imgArray'       => "data:image/png;base64," . base64_encode($imgArray),
                'authorImage'       => "data:image/png;base64," . base64_encode($authorImage),
                'author'       => $author,
                'likes'       => $likes
            );

            //array_push($users_arr['data'], $user_item);
            array_push($posts_arr, $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        $posts_arr = array();
        $post_item = array(
            'id_publication'    => null,
            'email'             => null,
            'description'       => null,
            'imgArray'             => null,
            'likes'       => null

        );
        array_push($posts_arr, $post_item);
        echo json_encode($posts_arr);
    }
}

function getPostById($id)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $result = $post->singleRead($id);
    if (empty($post->email)) {
        $post_arr = array(
            'id_publication'    => null,
            'description'       => null,
            'email'             => null,
            'image'             => null
        );
        echo json_encode($post_arr);
    } else {
        $post_arr = array(
            'id_publication'    => $post->id_publication,
            'description'       => $post->description,
            'email'             => $post->email,
            'image'             => $post->aux_image
        );
        echo json_encode($post_arr);
    }
}

function getPosts()
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $result = $post->read();
    $num = $result->rowCount();

    if ($num > 0) {
        $posts_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id_publication' => $id_publication,
                'description'    => $description,
                'email'          => $email,
                'imgArray'       => "data:image/png;base64," . base64_encode($imgArray),
                'authorImage'       => "data:image/png;base64," . base64_encode($authorImage),
                'author'       => $author,
                'likes'       => $likes
            );

            //array_push($users_arr['data'], $user_item);
            array_push($posts_arr, $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        $posts_arr = array();
        $post_item = array(
            'id_publication'    => null,
            'email'             => null,
            'description'       => null,
            'imgArray'             => null,
            'likes'       => null

        );
        array_push($posts_arr, $post_item);
        echo json_encode($posts_arr);
    }
}

function getUserPosts($email)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $result = $post->getUserPosts($email);
    $num = $result->rowCount();

    if ($num > 0) {
        $posts_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id_publication' => $id_publication,
                'description'    => $description,
                'email'          => $email,
                'imgArray'       => "data:image/png;base64," . base64_encode($imgArray),
                'authorImage'       => "data:image/png;base64," . base64_encode($authorImage),
                'author'       => $author,
                'likes'       => $likes
            );

            //array_push($users_arr['data'], $user_item);
            array_push($posts_arr, $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        $posts_arr = array();
        $post_item = array(
            'id_publication'    => null,
            'email'             => null,
            'description'       => null,
            'imgArray'             => null,
            'likes'       => null

        );
        array_push($posts_arr, $post_item);
        echo json_encode($posts_arr);
    }
}

function getUserLikedPosts($email)
{
    $database = new Database();
    $db = $database->connect();
    $post = new Publication($db);
    $result = $post->getUserLikedPosts($email);
    $num = $result->rowCount();

    if ($num > 0) {
        $posts_arr = array();
        //$users_arr['data'] = array();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            extract($row);

            $post_item = array(
                'id_publication' => $id_publication,
                'description'    => $description,
                'email'          => $email,
                'imgArray'       => "data:image/png;base64," . base64_encode($imgArray),
                'authorImage'       => "data:image/png;base64," . base64_encode($authorImage),
                'author'       => $author,
                'likes'       => $likes
            );

            //array_push($users_arr['data'], $user_item);
            array_push($posts_arr, $post_item);
        }

        echo json_encode($posts_arr);
    } else {
        $posts_arr = array();
        $post_item = array(
            'id_publication'    => null,
            'email'             => null,
            'description'       => null,
            'imgArray'             => null,
            'likes'       => null

        );
        array_push($posts_arr, $post_item);
        echo json_encode($posts_arr);
    }
}
