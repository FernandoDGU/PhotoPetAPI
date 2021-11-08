<?php
class Tag
{
    private $conn;
    private $table = 'tag';

    public $id_tag;
    public $tagname;

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
}
