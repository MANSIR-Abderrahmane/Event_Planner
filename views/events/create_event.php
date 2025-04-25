

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = $eventController->createEvent($_POST);
    $message = $result['message'];
    if ($result['success']) {
        header("Location: list_events.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <link rel="stylesheet" href="/SiiA/gestion_evenements/public/css/style.css">
</head>
<body>
    <div class="form-container">
        <h1>Create New Event</h1>

        <?php if ($message): ?>
            <div class="message <?= strpos($message, 'success') !== false ? 'success' : 'error' ?>">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" id="event-form">
            <div class="form-group">
                <label for="titre">Event Title:</label>
                <input type="text" id="titre" name="titre" required>
            </div>

            <div class="form-group">
                <label for="date_evenement">Event Date:</label>
                <input type="date" id="date_evenement" name="date_evenement" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn btn-success">Create Event</button>
        </form>

        <div class="nav-links">
            <a href="list_events.php">Back to Events List</a>
        </div>
    </div>
    <script src="/SiiA/gestion_evenements/public/js/script.js"></script>
</body>
</html>