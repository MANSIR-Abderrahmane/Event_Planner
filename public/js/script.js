// script.js - Main JavaScript file for the application

document.addEventListener('DOMContentLoaded', function() {
    // Form validation for create_event.php
    if (document.getElementById('event-form')) {
        setupEventFormValidation();
    }

    // Form validation for register_participant.php
    if (document.getElementById('participant-form')) {
        setupParticipantFormValidation();
    }

    // General UI enhancements
    setupUI();
});

function setupEventFormValidation() {
    const form = document.getElementById('event-form');
    
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('titre').value.trim();
        const date = document.getElementById('date_evenement').value;
        const today = new Date().toISOString().split('T')[0];
        
        if (title.length < 5) {
            alert('Event title must be at least 5 characters long');
            e.preventDefault();
            return;
        }
        
        if (date < today) {
            alert('Event date cannot be in the past');
            e.preventDefault();
            return;
        }
    });
}

function setupParticipantFormValidation() {
    const form = document.getElementById('participant-form');
    
    form.addEventListener('submit', function(e) {
        const name = document.getElementById('nom').value.trim();
        const email = document.getElementById('email').value.trim();
        const event = document.getElementById('event_id').value;
        
        if (name.length < 3) {
            alert('Name must be at least 3 characters long');
            e.preventDefault();
            return;
        }
        
        if (!validateEmail(email)) {
            alert('Please enter a valid email address');
            e.preventDefault();
            return;
        }
        
        if (!event) {
            alert('Please select an event');
            e.preventDefault();
            return;
        }
    });
}

function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function setupUI() {
    // Add fade-in animation to messages
    const messages = document.querySelectorAll('.message');
    messages.forEach(msg => {
        msg.style.opacity = '0';
        msg.style.transition = 'opacity 0.5s ease-in';
        setTimeout(() => {
            msg.style.opacity = '1';
        }, 100);
    });

    // Auto-hide success messages after 5 seconds
    const successMessages = document.querySelectorAll('.message.success');
    successMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.transition = 'opacity 0.5s ease-out';
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 500);
        }, 5000);
    });
}