<?php

class LikedPublication
{
    private $conn;
    private $table = 'liked_publications';

    public $id_liked_publications;
    public $id_publication;
    public $email;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertLike()
    {
        $query = 'INSERT INTO ' .
            $this->table . '
                SET
                id_publication = :id_publication,
                email = :email;
        ';
        $stmt = $this->conn->prepare($query);



        $this->id_publication = htmlspecialchars(strip_tags($this->id_publication));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':id_publication', $this->id_publication);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return $stmt->errorCode();
        }
    }

    public function deleteLike()
    {
        $query = 'DELETE FROM ' .
            $this->table . '
                WHERE
                id_publication = :id_publication
                AND
                email = :email;
        ';
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':id_publication', $this->id_publication);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return "ok";
        } else {
            return $stmt->errorCode();
        }
    }

    public function searchLike()
    {
        $query = 'SELECT 
                    id_liked_publications,
                    id_publication,
                    email
                  FROM 
                    ' . $this->table . '  
                  WHERE
                    email = "' . $this->email . '"
                    AND
                    id_publication = "' . $this->id_publication . '"';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            return 1;
        } else {
            return 0;
        }
    }
}
