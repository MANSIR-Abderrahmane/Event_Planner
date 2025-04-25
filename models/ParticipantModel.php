
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php
require_once __DIR__ . '/../config/Database.php';

class ParticipantModel extends Database {
    private $id;
    private $nom;
    private $email;
    private $cnx;

    public function __construct() {
        $this->cnx = $this->connection();
    }

    public function setParticipant($nom, $email) {
        $this->nom = $nom;
        $this->email = $email;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    // CREATE
    public function insert() {
        $q = 'INSERT INTO participants (nom, email) VALUES (:nom, :email)';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':email', $this->email);

        if ($stmt->execute()) {
            $this->id = $this->cnx->lastInsertId(); // Ensure ID is set
            return true;
        }
        return false;
    }

    // READ
    public function read($id = null) {
        if ($id) {
            $q = 'SELECT * FROM participants WHERE id = :id';
            $stmt = $this->cnx->prepare($q);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $q = 'SELECT * FROM participants ORDER BY nom ASC';
            $stmt = $this->cnx->query($q);
            return $stmt->fetchAll();
        }
    }

    // UPDATE
    public function update() {
        $q = 'UPDATE participants SET nom = :nom, email = :email WHERE id = :id';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':nom', $this->nom);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // DELETE
    public function delete() {
        $q = 'DELETE FROM participants WHERE id = :id';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Check if email exists
    public function emailExists($email) {
        $q = 'SELECT id FROM participants WHERE email = :email';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
}