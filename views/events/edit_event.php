
    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<?php
require_once __DIR__ . '/../../controllers/EventController.php';

$eventController = new EventController();
$message = '';

// Get event ID from URL
$event_id = isset($_GET['id']) ? $_GET['id'] : null;
if (!$event_id || !is_numeric($event_id)) {
    header("Location: list_events.php?error=Invalid event ID");
    exit();
}

// Fetch event data
$event = $eventController->getEvent($event_id);
if (!$event) {
    header("Location: list_events.php?error=Event not found");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $eventController->updateEvent($event_id, $_POST);
    if ($result['success']) {
        header("Location: list_events.php?success=Event updated successfully");
        exit();
    } else {
        $message = $result['message'];
        // Keep form values
        $event['titre'] = isset($_POST['titre']) ? $_POST['titre'] : $event['titre'];
        $event['date_evenement'] = isset($_POST['date_evenement']) ? $_POST['date_evenement'] : $event['date_evenement'];
        $event['description'] = isset($_POST['description']) ? $_POST['description'] : $event['description'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <link rel="stylesheet" href="/SiiA/gestion_evenements/public/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Edit Event: <?= htmlspecialchars($event['titre']) ?></h1>

        <?php if ($message): ?>
            <div class="message error">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="event-form">
            <div class="form-group">
                <label for="titre">Event Title:</label>
                <input type="text" id="titre" name="titre" 
                       value="<?= htmlspecialchars($event['titre']) ?>" required>
            </div>

            <div class="form-group">
                <label for="date_evenement">Event Date:</label>
                <input type="date" id="date_evenement" name="date_evenement" 
                       value="<?= htmlspecialchars($event['date_evenement']) ?>" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" required><?= 
                    htmlspecialchars($event['description']) 
                ?></textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Event</button>
                <a href="list_events.php" class="btn">Cancel</a>
            </div>
        </form>
    </div>

    <script src="/SiiA/gestion_evenements/public/js/script.js"></script>
</body>
</html>