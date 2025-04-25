
<!-- Correct path to your CSS file -->
<link rel="stylesheet" href="../../public/css/style.css">

<!-- Font Awesome for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php

require_once __DIR__ . '/../../controllers/InscriptionController.php';
require_once __DIR__ . '/../../controllers/EventController.php';
require_once __DIR__ . '/../../controllers/ParticipantController.php';

$inscriptionController = new InscriptionController();
$eventController = new EventController();
$participantController = new ParticipantController();

// Get all inscriptions with participant and event details
$inscriptions = $inscriptionController->getInscriptions();

// Handle deletion if requested
if (isset($_GET['delete'])) {
    $result = $inscriptionController->deleteInscription($_GET['delete']);
    if ($result['success']) {
        header("Location: list_inscriptions.php?success=" . urlencode($result['message']));
        exit();
    } else {
        $error = $result['message'];
    }
}

// Display success message if present
$success = isset($_GET['success']) ? $_GET['success'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registrations</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Event Registrations</h1>

        <?php if ($success): ?>
            <div class="message success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="../participants/register_participant.php" class="btn btn-primary">New Registration</a>
            <a href="../events/list_events.php" class="btn">View Events</a>
        </div>

        <?php if (count($inscriptions) > 0): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Participant</th>
                        <th>Event</th>
                        <th>Registration Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inscriptions as $inscription): ?>
                        <tr>
                            <td>
                                <a href="view_participant.php?id=<?= $inscription['participant_id'] ?>">
                                    <?= htmlspecialchars($inscription['participant_name']) ?>
                                </a>
                            </td>
                            <td>
                                <a href="view_event.php?id=<?= $inscription['event_id'] ?>">
                                    <?= htmlspecialchars($inscription['event_title']) ?>
                                </a>
                            </td>
                            <td><?= date('M j, Y H:i', strtotime($inscription['date_inscription'])) ?></td>
                            <td class="actions">
                                <a href="list_inscriptions.php?delete=<?= $inscription['id'] ?>" 
                                   class="btn btn-sm btn-danger" 
                                   onclick="return confirm('Are you sure you want to delete this registration?')">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message info">
                No registrations found. <a href="../../participants/register_participant.php">Register a participant</a> for an event.
            </div>
        <?php endif; ?>
    </div>

    <script src="../public/js/script.js"></script>
</body>
</html>