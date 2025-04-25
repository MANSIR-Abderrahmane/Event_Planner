<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> | Event Management</title>
    
    <!-- CSS files -->
    <link rel="stylesheet" href="../public/css/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts - Updated with more variety -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
</head>

<body>
<?php
// Set page title
$pageTitle = "Dashboard";

// Path to header.php
require_once __DIR__ . '/layout/header.php';

// Path to controllers (going up one level from views)
require_once __DIR__ . '/../controllers/EventController.php';
require_once __DIR__ . '/../controllers/ParticipantController.php';
require_once __DIR__ . '/../controllers/InscriptionController.php';

$eventController = new EventController();
$participantController = new ParticipantController();
$inscriptionController = new InscriptionController();

// Get counts for dashboard
$eventCount = count($eventController->getEvents());
$participantCount = count($participantController->getParticipants());
$registrationCount = count($inscriptionController->getInscriptions());

// Get upcoming events (with some date logic)
$events = $eventController->getEvents();
$upcomingEvents = array_filter($events, function($event) {
    return strtotime($event['date_evenement']) >= strtotime('today');
});
usort($upcomingEvents, function($a, $b) {
    return strtotime($a['date_evenement']) - strtotime($b['date_evenement']);
});
$upcomingEvents = array_slice($upcomingEvents, 0, 3);
?>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
        <p class="welcome-message">Welcome back! Here's your event management overview.</p>
    </div>
    
    <div class="stats-container">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $eventCount ?></div>
                <div class="stat-label">Total Events</div>
            </div>
            <a href="events/list_events.php" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $participantCount ?></div>
                <div class="stat-label">Participants</div>
            </div>
            <a href="participants/list_participants.php" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-ticket-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-value"><?= $registrationCount ?></div>
                <div class="stat-label">Registrations</div>
            </div>
            <a href="inscriptions/list_inscriptions.php" class="stat-link">
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="quick-actions-section">
            <div class="section-header">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            </div>
            <div class="action-buttons">
                <a href="events/create_event.php" class="action-btn create-event">
                    <i class="fas fa-calendar-plus"></i>
                    <span>Create Event</span>
                </a>
                <a href="participants/register_participant.php" class="action-btn register-participant">
                    <i class="fas fa-user-plus"></i>
                    <span>Add Participant</span>
                </a>
                <a href="inscriptions/create_inscription.php" class="action-btn create-registration">
                    <i class="fas fa-clipboard-list"></i>
                    <span>New Registration</span>
                </a>
                <a href="reports/generate_report.php" class="action-btn generate-report">
                    <i class="fas fa-chart-bar"></i>
                    <span>Generate Report</span>
                </a>
            </div>
        </div>
        
        <div class="dashboard-panels">
            <div class="panel upcoming-events">
                <div class="panel-header">
                    <h2><i class="fas fa-calendar-day"></i> Upcoming Events</h2>
                    <a href="events/list_events.php" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                
                <div class="panel-content">
                    <?php if (count($upcomingEvents) > 0): ?>
                        <?php foreach ($upcomingEvents as $event): ?>
                            <div class="event-card">
                                <div class="event-date">
                                    <div class="month"><?= date('M', strtotime($event['date_evenement'])) ?></div>
                                    <div class="day"><?= date('d', strtotime($event['date_evenement'])) ?></div>
                                </div>
                                <div class="event-info">
                                    <h3 class="event-title"><?= htmlspecialchars($event['titre']) ?></h3>
                                    <p class="event-desc"><?= htmlspecialchars(substr($event['description'], 0, 80)) ?>...</p>
                                    <div class="event-meta">
                                        <span><i class="fas fa-map-marker-alt"></i><?php echo htmlspecialchars(isset($event['lieu']) ? $event['lieu'] : 'Location TBD'); ?></span>
                                        <span><i class="fas fa-clock"></i><?php echo date('g:i A', strtotime(isset($event['date_evenement']) ? $event['date_evenement'] : 'now')); ?></span>
                                    </div>
                                </div>
                                <div class="event-actions">
                                    <a href="events/view_event.php?id=<?= $event['id'] ?>" class="btn btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="inscriptions/create_inscription.php?event_id=<?= $event['id'] ?>" class="btn btn-register">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-calendar-xmark"></i></div>
                            <h3>No Upcoming Events</h3>
                            <p>You don't have any upcoming events scheduled.</p>
                            <a href="events/create_event.php" class="btn btn-primary">Create Your First Event</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="panel recent-registrations">
                <div class="panel-header">
                    <h2><i class="fas fa-clipboard-check"></i> Recent Registrations</h2>
                    <a href="inscriptions/list_inscriptions.php" class="view-all">View All <i class="fas fa-chevron-right"></i></a>
                </div>
                
                <div class="panel-content">
                    <?php
                    $registrations = array_slice($inscriptionController->getInscriptions(), 0, 5);
                    if (count($registrations) > 0):
                    ?>
                        <div class="registration-list">
                            <?php foreach ($registrations as $registration): 
                                $participant = $participantController->getParticipantById($registration['participant_id']);
                                $event = $eventController->getEventById($registration['evenement_id']);
                            ?>
                                <div class="registration-item">
                                    <div class="registration-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="registration-details">
                                        <div class="registration-name">
                                            <?= htmlspecialchars($participant['nom'] . ' ' . $participant['prenom']) ?>
                                        </div>
                                        <div class="registration-event">
                                            <i class="fas fa-calendar-alt"></i> <?= htmlspecialchars($event['titre']) ?>
                                        </div>
                                    </div>
                                    <div class="registration-date">
                                        <?= date('M d, Y', strtotime($registration['date_inscription'])) ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-icon"><i class="fas fa-ticket-alt"></i></div>
                            <h3>No Registrations Yet</h3>
                            <p>Once participants register for events, you'll see them here.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
// Path to footer.php
require_once __DIR__ . '/layout/footer.php'; 
?>

<style>
/* Add these styles to your CSS file or include them inline for the upgraded dashboard */
:root {
    --primary-color: #4361ee;
    --secondary-color: #3a0ca3;
    --success-color: #2ec4b6;
    --warning-color: #ff9f1c;
    --danger-color: #e71d36;
    --light-color: #f8f9fa;
    --dark-color: #212529;
    --gray-color: #6c757d;
    --light-gray: #e9ecef;
    --white: #ffffff;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    --border-radius: 8px;
    --transition: all 0.3s ease;
}

body {
    font-family: 'Poppins', sans-serif;
    background-color: #f4f7fc;
    color: #333;
    line-height: 1.6;
    margin: 0;
    padding: 0;
}

.dashboard-container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 0 20px;
}

.dashboard-header {
    margin-bottom: 20px;
    text-align: left;
}

.dashboard-header h1 {
    font-size: 28px;
    font-weight: 600;
    color: var(--dark-color);
    margin-bottom: 5px;
}

.welcome-message {
    color: var(--gray-color);
    font-size: 16px;
    margin-top: 0;
}

/* Stats Container */
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    padding: 20px;
    display: flex;
    align-items: center;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    background-color: rgba(67, 97, 238, 0.1);
    color: var(--primary-color);
    height: 60px;
    width: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    margin-right: 20px;
}

.stat-content {
    flex-grow: 1;
}

.stat-value {
    font-size: 32px;
    font-weight: 700;
    color: var(--dark-color);
    line-height: 1;
    margin-bottom: 5px;
}

.stat-label {
    color: var(--gray-color);
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.stat-link {
    position: absolute;
    top: 20px;
    right: 20px;
    color: var(--primary-color);
    font-size: 18px;
    opacity: 0.7;
    transition: var(--transition);
}

.stat-link:hover {
    opacity: 1;
}

/* Quick Actions Section */
.quick-actions-section {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    padding: 25px;
    margin-bottom: 30px;
}

.section-header {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
}

.section-header h2 {
    font-size: 20px;
    font-weight: 600;
    margin: 0;
}

.section-header i {
    margin-right: 10px;
    color: var(--primary-color);
}

.action-buttons {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 15px;
}

.action-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    padding: 20px;
    text-decoration: none;
    color: var(--dark-color);
    transition: var(--transition);
    text-align: center;
    border: 1px solid var(--light-gray);
}

.action-btn i {
    font-size: 24px;
    margin-bottom: 10px;
}

.action-btn span {
    font-weight: 500;
}

.action-btn.create-event {
    color: #4361ee;
}

.action-btn.register-participant {
    color: #3a0ca3;
}

.action-btn.create-registration {
    color: #2ec4b6;
}

.action-btn.generate-report {
    color: #ff9f1c;
}

.action-btn:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow);
}

/* Dashboard Panels */
.dashboard-panels {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.panel {
    background-color: var(--white);
    border-radius: var(--border-radius);
    box-shadow: var(--shadow);
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.panel-header {
    padding: 20px;
    border-bottom: 1px solid var(--light-gray);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-header h2 {
    font-size: 18px;
    font-weight: 600;
    margin: 0;
    display: flex;
    align-items: center;
}

.panel-header h2 i {
    margin-right: 10px;
    color: var(--primary-color);
}

.view-all {
    color: var(--primary-color);
    font-size: 14px;
    text-decoration: none;
    font-weight: 500;
}

.view-all i {
    font-size: 12px;
    margin-left: 5px;
}

.panel-content {
    padding: 20px;
    flex-grow: 1;
    overflow: auto;
}

/* Event Cards */
.event-card {
    display: flex;
    margin-bottom: 20px;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: var(--transition);
    border: 1px solid var(--light-gray);
}

.event-card:last-child {
    margin-bottom: 0;
}

.event-card:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow);
}

.event-date {
    background-color: var(--primary-color);
    color: white;
    padding: 15px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-width: 80px;
    text-align: center;
}

.event-date .month {
    font-size: 14px;
    text-transform: uppercase;
    font-weight: 500;
}

.event-date .day {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}

.event-info {
    padding: 15px;
    flex-grow: 1;
}

.event-title {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 5px 0;
    color: var(--dark-color);
}

.event-desc {
    color: var(--gray-color);
    margin: 0 0 10px 0;
    font-size: 14px;
}

.event-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    font-size: 12px;
    color: var(--gray-color);
}

.event-meta span {
    display: flex;
    align-items: center;
}

.event-meta i {
    margin-right: 5px;
}

.event-actions {
    display: flex;
    flex-direction: column;
    justify-content: center;
    padding: 15px;
    gap: 10px;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    transition: var(--transition);
}

.btn i {
    margin-right: 5px;
}

.btn-view {
    background-color: #e9ecef;
    color: var(--dark-color);
}

.btn-register {
    background-color: var(--primary-color);
    color: white;
}

.btn-primary {
    background-color: var(--primary-color);
    color: white;
}

.btn:hover {
    opacity: 0.9;
}

/* Registration List */
.registration-list {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.registration-item {
    display: flex;
    align-items: center;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: var(--border-radius);
    transition: var(--transition);
    border: 1px solid var(--light-gray);
}

.registration-item:hover {
    transform: translateX(5px);
    box-shadow: var(--shadow);
}

.registration-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: rgba(67, 97, 238, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary-color);
    font-size: 20px;
    margin-right: 15px;
}

.registration-details {
    flex-grow: 1;
}

.registration-name {
    font-weight: 600;
    font-size: 16px;
    color: var(--dark-color);
}

.registration-event {
    font-size: 13px;
    color: var(--gray-color);
}

.registration-date {
    font-size: 12px;
    color: var(--gray-color);
    text-align: right;
}

/* Empty State */
.empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 30px 20px;
    text-align: center;
}

.empty-icon {
    font-size: 48px;
    color: var(--light-gray);
    margin-bottom: 15px;
}

.empty-state h3 {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 10px 0;
    color: var(--dark-color);
}

.empty-state p {
    font-size: 14px;
    color: var(--gray-color);
    margin-bottom: 20px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .stats-container {
        grid-template-columns: 1fr;
    }
    
    .dashboard-panels {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .event-card {
        flex-direction: column;
    }
    
    .event-date {
        flex-direction: row;
        justify-content: flex-start;
        gap: 10px;
        width: 100%;
        min-width: auto;
    }
    
    .event-actions {
        flex-direction: row;
    }
}
</style>

<script>
    // Optional JavaScript for enhanced interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effect for panels
        const panels = document.querySelectorAll('.panel');
        panels.forEach(panel => {
            panel.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 10px 20px rgba(0, 0, 0, 0.1)';
            });
            
            panel.addEventListener('mouseleave', function() {
                this.style.boxShadow = '0 4px 6px rgba(0, 0, 0, 0.1)';
            });
        });
        
        // You could add more interactive elements here
    });
</script>
</body>
</html>