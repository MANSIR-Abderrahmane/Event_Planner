

    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"><?php
require_once __DIR__ . '/../../controllers/ParticipantController.php';
require_once __DIR__ . '/../../controllers/EventController.php';
require_once __DIR__ . '/../../controllers/InscriptionController.php';

$participantController = new ParticipantController();
$eventController = new EventController();
$inscriptionController = new InscriptionController();

$events = $eventController->getEvents();
$message = '';
$formData = ['nom' => '', 'email' => '', 'event_id' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formData = [
        'nom' => trim($_POST['nom']),
        'email' => trim($_POST['email']),
        'event_id' => (int)$_POST['event_id'] // Ensure event_id is integer
    ];

    // Register participant
    $participantResult = $participantController->registerParticipant($formData);
    
    if ($participantResult['success']) {
        // Debug output (remove in production)
        error_log("Participant ID: " . $participantResult['participant_id']);
        error_log("Event ID: " . $formData['event_id']);
        
        // Create inscription
        $inscriptionResult = $inscriptionController->createInscription(
            $participantResult['participant_id'],
            $formData['event_id']
        );
        
        if ($inscriptionResult['success']) {
            $message = 'Registration successful!';
            $formData = ['nom' => '', 'email' => '', 'event_id' => ''];
        } else {
            $message = 'Registration completed but event signup failed: ' . $inscriptionResult['message'];
        }
    } else {
        $message = 'Registration failed: ' . $participantResult['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Participant Registration</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Participant Registration</h1>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="participant-form">
            <div class="form-group">
                <label for="nom">Full Name:</label>
                <input type="text" id="nom" name="nom" 
                       value="<?= htmlspecialchars($formData['nom']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       value="<?= htmlspecialchars($formData['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="event_id">Select Event:</label>
                <select id="event_id" name="event_id" required>
                    <option value="">-- Select an event --</option>
                    <?php foreach ($events as $event): ?>
                        <option value="<?= $event['id'] ?>" 
                            <?= $formData['event_id'] == $event['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($event['titre']) ?> 
                            (<?= date('M j, Y', strtotime($event['date_evenement'])) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <div class="nav-links">
            <a href="../events/list_events.php">View Events</a>
            <a href="../inscriptions/list_inscriptions.php">View Registrations</a>
        </div>
    </div>
    <script src="../../public/js/script.js"></script>
</body>
</html>