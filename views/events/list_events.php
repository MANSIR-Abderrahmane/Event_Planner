
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<?php
require_once __DIR__ . '/../../controllers/EventController.php';
require_once __DIR__ . '/../../controllers/InscriptionController.php';

$eventController = new EventController();
$inscriptionController = new InscriptionController();

// Get all events
$events = $eventController->getEvents();

// Handle deletion if requested
if (isset($_GET['delete'])) {
    $result = $eventController->deleteEvent($_GET['delete']);
    if ($result['success']) {
        header("Location: list_events.php?success=" . urlencode($result['message']));
        exit();
    } else {
        $error = $result['message'];
    }
}

// Display messages
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : (isset($error) ? $error : '');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event List</title>
    <link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Event Management</h1>

        <?php if ($success): ?>
            <div class="message success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="message error">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="actions">
            <a href="create_event.php" class="btn btn-primary">Create New Event</a>
            <a href="../inscriptions/list_inscriptions.php" class="btn">View Registrations</a>
        </div>

        <?php if (count($events) > 0): ?>
            <div class="event-cards">
                <?php foreach ($events as $event): 
                    $registrations = $inscriptionController->getEventRegistrations($event['id']);
                ?>
            <div class="event-card">
                <div class="event-header">
                    <h2><?= htmlspecialchars($event['titre']) ?></h2>
                        <span class="event-date">
                            <?= date('M j, Y', strtotime($event['date_evenement'])) ?>
                        </span>
                </div>
                        
                <div class="event-description">
        <?= nl2br(htmlspecialchars($event['description'])) ?>
    </div>
    
    <div class="event-stats">
        <span class="registrations-count">
            <?= count($registrations) ?> registration(s)
        </span>
    </div>
                        
    <div class="event-actions">
        <a href="edit_event.php?id=<?= $event['id'] ?>" class="btn btn-sm">Edit</a>
        <a href="list_events.php?delete=<?= $event['id'] ?>" 
           class="btn btn-sm btn-danger"
           onclick="return confirm('Delete this event and all its registrations?')">
            Delete
        </a>
    </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="message info">
                No events found. <a href="create_event.php">Create your first event</a>.
            </div>
        <?php endif; ?>
    </div>

    <script src="../../public/js/script.js"></script>
</body>
</html>