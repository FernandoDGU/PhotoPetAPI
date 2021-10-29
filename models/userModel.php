<?php
class User
{
    private $conn;
    private $table = 'user';

    public $email;
    public $fullname;
    public $firstname;
    public $lastname;
    public $password;
    public $phone;
    public $description;
    public $image;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function read()
    {
        $query = 'SELECT 
                    email,
                    fullname,
                    firstname,
                    lastname,
                    password,
                    phone,
                    description,
                    image
                  FROM 
                    ' . $this->table . '  
                ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();

        return $stmt;
    }

    public function singleRead($id)
    {
        $query = 'SELECT 
                    email,
                    fullname,
                    firstname,
                    lastname,
                    password,
                    phone,
                    description,
                    image
                  FROM 
                    ' . $this->table . '  
                  WHERE
                    email = "' . $id . '"
                    ';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->email = $row['email'];
            $this->fullname = $row['fullname'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->description = $row['description'];
            $this->image = "data:image/png;base64," . base64_encode($row['image']);
        }
    }

    public function userLogged($email, $pass)
    {
        $query = 'SELECT 
                    email,
                    fullname,
                    firstname,
                    lastname,
                    password,
                    phone,
                    description,
                    image
                  FROM 
                    ' . $this->table . '  
                  WHERE
                    email = "' . $email . '"
                    AND
                    password = "' . $pass . '"';

        $stmt = $this->conn->prepare($query);

        $stmt->execute();
        $num = $stmt->rowCount();

        if ($num > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->email = $row['email'];
            $this->fullname = $row['fullname'];
            $this->firstname = $row['firstname'];
            $this->lastname = $row['lastname'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->description = $row['description'];
            $this->image = "data:image/png;base64," . base64_encode($row['image']);
        }
    }

    public function insertUser()
    {
        $query = 'INSERT INTO ' .
            $this->table . '
                SET
                email = :email,
                fullname = :fullname,
                firstname = :firstname,
                lastname = :lastname,
                password = :password,
                phone = :phone,
                description = :description,
                image = :image
        ';
        $stmt = $this->conn->prepare($query);

        if (empty($this->email)) {
            $this->email = null;
        }

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->description = htmlspecialchars(strip_tags($this->description));

        list($type, $this->image) = explode(';', $this->image);
        list(, $this->image)      = explode(',', $this->image);
        $this->image = base64_decode($this->image);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);




        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function updateUser()
    {
        $query = 'UPDATE ' .
            $this->table . '
                SET
                fullname = :fullname,
                firstname = :firstname,
                lastname = :lastname,
                password = :password,
                phone = :phone,
                description = :description,
                image = :image
                WHERE email = :email
        ';
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->fullname = htmlspecialchars(strip_tags($this->fullname));
        $this->firstname = htmlspecialchars(strip_tags($this->firstname));
        $this->lastname = htmlspecialchars(strip_tags($this->lastname));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->description = htmlspecialchars(strip_tags($this->description));

        list($type, $this->image) = explode(';', $this->image);
        list(, $this->image)      = explode(',', $this->image);
        $this->image = base64_decode($this->image);

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':fullname', $this->fullname);
        $stmt->bindParam(':firstname', $this->firstname);
        $stmt->bindParam(':lastname', $this->lastname);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':image', $this->image);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }

    public function deleteUser()
    {
        $query = 'DELETE FROM ' .
            $this->table . '
                WHERE email = :email
        ';
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            return "ok";
        }

        return $stmt->errorCode();
    }
}
