
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php
require_once __DIR__ . '/../models/ParticipantModel.php';

class ParticipantController {
    public function registerParticipant($data) {
        try {
            // Validate required fields
            if (empty($data['nom']) || empty($data['email'])) {
                throw new Exception('Name and email are required');
            }

            $model = new ParticipantModel();
            $model->setParticipant(
                htmlspecialchars($data['nom']),
                htmlspecialchars($data['email'])
            );

            if ($model->insert()) {
                // Ensure we have the inserted ID
                $participantId = $model->getId();
                if (!$participantId) {
                    throw new Exception('Failed to get participant ID after registration');
                }

                return [
                    'success' => true,
                    'message' => 'Registration successful',
                    'participant_id' => $participantId
                ];
            }
            throw new Exception('Failed to save participant');
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getParticipants() {
        $model = new ParticipantModel();
        return $model->read();
    }

    public function getParticipant($id) {
        $model = new ParticipantModel();
        return $model->read($id);
    }
}