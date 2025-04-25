
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php
require_once __DIR__ . '/../config/Database.php';

class InscriptionModel extends Database {
    private $id;
    private $event_id;
    private $participant_id;
    private $cnx;

    public function __construct() {
        $this->cnx = $this->connection();
    }

    public function setInscription($event_id, $participant_id) {
        $this->event_id = $event_id;
        $this->participant_id = $participant_id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    // CREATE
    public function insert() {
        $q = 'INSERT INTO inscriptions (event_id, participant_id, date_inscription) 
              VALUES (:event_id, :participant_id, NOW())';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':event_id', $this->event_id);
        $stmt->bindParam(':participant_id', $this->participant_id);
        return $stmt->execute();
    }

    // READ
    public function read($id = null) {
        if ($id) {
            $q = 'SELECT i.*, p.nom AS participant_name, e.titre AS event_title 
                  FROM inscriptions i
                  JOIN participants p ON i.participant_id = p.id
                  JOIN events e ON i.event_id = e.id
                  WHERE i.id = :id';
            $stmt = $this->cnx->prepare($q);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $q = 'SELECT i.*, p.nom AS participant_name, e.titre AS event_title 
                  FROM inscriptions i
                  JOIN participants p ON i.participant_id = p.id
                  JOIN events e ON i.event_id = e.id
                  ORDER BY i.date_inscription DESC';
            $stmt = $this->cnx->query($q);
            return $stmt->fetchAll();
        }
    }

    // Check if participant is already registered for event
    public function exists($event_id, $participant_id) {
        $q = 'SELECT id FROM inscriptions 
              WHERE event_id = :event_id AND participant_id = :participant_id';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->bindParam(':participant_id', $participant_id);
        $stmt->execute();
        return $stmt->fetch();
    }

    // DELETE
    public function delete() {
        $q = 'DELETE FROM inscriptions WHERE id = :id';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':id', $this->id);
        return $stmt->execute();
    }

    // Get inscriptions by event
    public function getByEvent($event_id) {
        $q = 'SELECT i.*, p.nom, p.email 
              FROM inscriptions i
              JOIN participants p ON i.participant_id = p.id
              WHERE i.event_id = :event_id
              ORDER BY i.date_inscription DESC';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':event_id', $event_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get inscriptions by participant
    public function getByParticipant($participant_id) {
        $q = 'SELECT i.*, e.titre, e.date_evenement 
              FROM inscriptions i
              JOIN events e ON i.event_id = e.id
              WHERE i.participant_id = :participant_id
              ORDER BY e.date_evenement DESC';
        $stmt = $this->cnx->prepare($q);
        $stmt->bindParam(':participant_id', $participant_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}