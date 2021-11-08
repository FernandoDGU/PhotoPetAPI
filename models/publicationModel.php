<?php

class Publication
{
    private $conn;
    private $table = 'publication';

    private $id_publication;
    private $description;
    private $email;

    private $aux_image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'CALL Post_images();';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function singleRead($id)
    {
        $query = 'CALL Post_images_id(' . $id . ')';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_publication   = $row['id_publication'];
            $this->description      = $row['description'];
            $this->email            = $row['email'];

            $this->aux_image = "data:image/png;base64," . base64_encode($row['image']);
        }
    }

    public function readUserPosts($email)
    {
        $query = 'CALL Posts_images_user("' . $email . '")';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id_publication   = $row['id_publication'];
            $this->description      = $row['description'];
            $this->email            = $row['email'];

            $this->aux_image = "data:image/png;base64," . base64_encode($row['image']);
        }
    }

    public function insertPost()
    {
        $query = 'INSERT INTO ' .
            $this->table . '
                SET
                description = :description,
                email = :email
        ';
        $stmt = $this->conn->prepare($query);



        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':email', $this->email);




        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function updatePost()
    {
        $query = 'UPDATE ' .
            $this->table . '
                SET
                description = :description
                WHERE id_publication = :id_publication
        ';
        $stmt = $this->conn->prepare($query);

        $this->description = htmlspecialchars(strip_tags($this->description));
        $this->id_publication = htmlspecialchars(strip_tags($this->id_publication));

        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':id_publication', $this->id_publication);


        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function deletePost()
    {
        $query = 'DELETE FROM ' .
            $this->table . '
                WHERE id_publication = :id_publication
        ';
        $stmt = $this->conn->prepare($query);

        $this->id_publication = htmlspecialchars(strip_tags($this->id_publication));

        $stmt->bindParam(':id_publication', $this->id_publication);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }
}
