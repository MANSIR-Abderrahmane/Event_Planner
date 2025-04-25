
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php
require_once __DIR__ . '/../models/InscriptionModel.php';

class InscriptionController {
    public function createInscription($participant_id, $event_id) {
        try {
            // Validate IDs
            if (!is_numeric($participant_id) || !is_numeric($event_id)) {
                throw new Exception('Invalid participant or event ID');
            }

            $model = new InscriptionModel();
            $model->setInscription($event_id, $participant_id);

            if ($model->insert()) {
                return [
                    'success' => true,
                    'message' => 'Successfully registered for event'
                ];
            }
            throw new Exception('Failed to create registration');
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getInscriptions() {
        $model = new InscriptionModel();
        return $model->read();
    }

    public function getInscription($id) {
        $model = new InscriptionModel();
        return $model->read($id);
    }

    public function getEventRegistrations($event_id) {
        $model = new InscriptionModel();
        return $model->getByEvent($event_id);
    }

    public function getParticipantRegistrations($participant_id) {
        $model = new InscriptionModel();
        return $model->getByParticipant($participant_id);
    }

    public function deleteInscription($id) {
        try {
            $model = new InscriptionModel();
            $model->setId($id);
            
            if ($model->delete()) {
                return [
                    'success' => true,
                    'message' => 'Registration deleted successfully'
                ];
            }
            throw new Exception('Failed to delete registration');
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}