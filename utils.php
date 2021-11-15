<?php

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once 'database.php';
include_once 'models/superModel.php';

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        break;

    case 'GET':
        if (isset($_GET['getAllData']))
            getAllData();
        break;

    case 'PUT':

        break;

    case 'DELETE':

        break;
}

function getAllData()
{
    $database = new Database();
    $db = $database->connect();
    $super = new SuperClass($db);
    $user = $super->getUsers();
    $tag = $super->getTags();
    $post = $super->getPosts();
    $album = $super->getAlbums();
    $likepublication = $super->getLikePublication();
    $publicationtag = $super->getPublicationTag();

    $object = array(
        'users' => $user,
        'tags' => $tag,
        'publications' => $post,
        'albums' => $album,
        'likepublication' => $likepublication,
        'publicationtag' => $publicationtag,
    );

    echo json_encode($object);
}
