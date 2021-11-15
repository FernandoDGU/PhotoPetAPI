<?php
class SuperClass
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function getUsers()
    {
        $query = 'SELECT 
                    email,
                    fullname,
                    firstname,
                    lastname,
                    phone,
                    description,
                    image
                  FROM 
                    ' . 'user' . '  
                ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $users_arr = array();
            //$users_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $user_item = array(
                    'email'         => $email,
                    'fullname'          => $fullname,
                    'firstname'          => $firstname,
                    'lastname'          => $lastname,
                    'password'      => null,
                    'phone'         => $phone,
                    'description'   => $description,
                    'image'         => "data:image/png;base64," . base64_encode($image)
                );

                //array_push($users_arr['data'], $user_item);
                array_push($users_arr, $user_item);
            }

            return $users_arr;
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
            return $user_arr;
        }
    }

    public function getTags()
    {
        $query = 'SELECT 
                    id_tag,
                    tagname
                  FROM 
                    ' . 'tag' . '  
                ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {

            //$users_arr['data'] = array();

            $tags_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $tag_item = array(
                    'id_tag' => $id_tag,
                    'tagname' => $tagname
                );

                //array_push($users_arr['data'], $user_item);
                array_push($tags_arr, $tag_item);
            }

            return $tags_arr;
        } else {
            $tags_arr = array();
            $tag_item = array(
                'id_tag' => null,
                'tagname' => null
            );
            array_push($tags_arr, $tag_item);

            return $tags_arr;
        }
    }

    public function getPosts()
    {
        $query = 'CALL Post_images();';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        $num = $stmt->rowCount();

        if ($num > 0) {
            $posts_arr = array();
            //$users_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $post_item = array(
                    'id_publication' => $id_publication,
                    'description'    => $description,
                    'email'          => $email,
                    'imgArray'       => null,
                    'authorImage'       => null,
                    'author'       => null,
                    'likes'       => $likes
                );

                //array_push($users_arr['data'], $user_item);
                array_push($posts_arr, $post_item);
            }

            return $posts_arr;
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
            return $posts_arr;
        }
    }

    public function getAlbums()
    {
        $query = 'SELECT 
                id_album,
                id_publication,
                image,
                description
                FROM
                ' . 'album' . '
        ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $albums_arr = array();
            //$users_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
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

            return $albums_arr;
        } else {
            $albums_arr = array();
            $album_item = array(
                'id_album' => null,
                'id_publication'    => null,
                'description'          => null,
                'imageString'       => null

            );
            array_push($albums_arr, $album_item);
            return $albums_arr;
        }
    }

    public function getLikePublication()
    {
        $query = 'SELECT 
                    id_liked_publications,
                    id_publication,
                    email
                  FROM 
                    ' . 'liked_publications' . '
                    ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $lp_arr = array();
            //$users_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $lp_item = array(
                    'id_liked_publications' => $id_liked_publications,
                    'id_publication'    => $id_publication,
                    'email'          => $email
                );

                //array_push($users_arr['data'], $user_item);
                array_push($lp_arr, $lp_item);
            }

            return $lp_arr;
        } else {
            $lp_arr = array();
            $lp_item = array(
                'id_liked_publications' => null,
                'id_publication'    => null,
                'email'          => null

            );
            array_push($lp_arr, $lp_item);
            return $lp_arr;
        }
    }

    public function getPublicationTag()
    {
        $query = 'SELECT 
                    id_publication_tag,
                    id_publication,
                    id_tag
                  FROM 
                    ' . 'publication_tag' . '
                    ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $pt_arr = array();
            //$users_arr['data'] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                $pt_item = array(
                    'id_publication_tag' => $id_publication_tag,
                    'id_publication'    => $id_publication,
                    'id_tag'          => $id_tag
                );

                //array_push($users_arr['data'], $user_item);
                array_push($pt_arr, $pt_item);
            }

            return $pt_arr;
        } else {
            $pt_arr = array();
            $pt_item = array(
                'id_publication_tag' => null,
                'id_publication'    => null,
                'id_tag'          => null

            );
            array_push($pt_arr, $pt_item);
            return $pt_arr;
        }
    }
}
