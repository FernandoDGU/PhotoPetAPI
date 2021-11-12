<?php
class Tag
{
    private $conn;
    private $table = 'tag';
    private $table2 = 'publication_tag';

    public $id_tag;
    public $tagname;

    public $id_publication;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertTag()
    {
        $query = 'INSERT INTO ' .
            $this->table . '
                SET
                tagname = :tagname;
        ';
        $stmt = $this->conn->prepare($query);



        $this->tagname = htmlspecialchars(strip_tags($this->tagname));

        $stmt->bindParam(':tagname', $this->tagname);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function insertTagPublication()
    {
        $query = 'INSERT INTO ' .
            $this->table2 . '
                SET
                id_publication = :id_publication,
                id_tag = :id_tag;
        ';
        $stmt = $this->conn->prepare($query);



        $this->id_publication = htmlspecialchars(strip_tags($this->id_publication));
        $this->id_tag = htmlspecialchars(strip_tags($this->id_tag));

        $stmt->bindParam(':id_publication', $this->id_publication);
        $stmt->bindParam(':id_tag', $this->id_tag);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function read()
    {
        $query = 'SELECT 
                    id_tag,
                    tagname
                  FROM 
                    ' . $this->table . '  
                ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function getTagsPost($id_post)
    {
        $query = 'CALL Tags_by_Post(' . $id_post . ');';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
