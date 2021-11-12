<?php
class Album
{
    private $conn;
    private $table = 'album';

    public $id_album;
    public $id_publication;
    public $image;
    public $description;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function insertAlbum()
    {
        $query = 'INSERT INTO ' .
            $this->table . '
                SET
                id_publication = :id_publication,
                image = :image,
                description = :description
        ';
        $stmt = $this->conn->prepare($query);



        $this->id_publication = htmlspecialchars(strip_tags($this->id_publication));

        list($type, $this->image) = explode(';', $this->image);
        list(, $this->image)      = explode(',', $this->image);
        $this->image = base64_decode($this->image);

        $this->description = htmlspecialchars(strip_tags($this->description));

        $stmt->bindParam(':id_publication', $this->id_publication);
        $stmt->bindParam(':image', $this->image);
        $stmt->bindParam(':description', $this->description);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function getAlbumsPost($id_post)
    {
        $query = 'SELECT 
                id_album,
                id_publication,
                image,
                description
                FROM
                ' . $this->table . '
                WHERE
                id_publication = ' . $id_post . '
        ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }
}
