

    <!-- Correct path to your CSS file -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet"><?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System<?= isset($pageTitle) ? " | $pageTitle" : '' ?></title>
    <link rel="stylesheet" href="/SiiA/gestion_evenements/public/css/style.css">
</head>
<body>
    <header class="main-header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo">
                    <a href="index.php">EventMS</a>
                </h1>
                <nav class="main-nav">
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="events/list_events.php">Events</a></li>
                        <li><a href="inscriptions/list_inscriptions.php">Registrations</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">