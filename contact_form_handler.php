<?php
// contact_form_handler.php

// Define your recipient email and other details
$recipient = 'support@myshop.com'; // Your support email address
$subject = 'Contact Form Submission from MyShop';
$successPage = 'thank_you.php'; // Page to redirect to after successful submission
$errorPage = 'contact.php'; // Page to redirect to after an error

// Function to sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Initialize variables and error array
$name = $email = $subject = $message = '';
$errors = [];

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $name = sanitizeInput($_POST['name']);
    $email = sanitizeInput($_POST['email']);
    $subject = sanitizeInput($_POST['subject']);
    $message = sanitizeInput($_POST['message']);

    // Validate name
    if (empty($name)) {
        $errors[] = 'Full name is required.';
    }

    // Validate email
    if (empty($email)) {
        $errors[] = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email format.';
    }

    // Validate subject
    if (empty($subject)) {
        $errors[] = 'Subject is required.';
    }

    // Validate message
    if (empty($message)) {
        $errors[] = 'Message is required.';
    }

    // If no errors, process the form
    if (empty($errors)) {
        // Compose the email
        $emailMessage = "Name: $name\n";
        $emailMessage .= "Email: $email\n";
        $emailMessage .= "Subject: $subject\n\n";
        $emailMessage .= "Message:\n$message\n";

        // Set email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        // Send the email
        if (mail($recipient, $subject, $emailMessage, $headers)) {
            // Redirect to the success page
            header("Location: $successPage");
            exit();
        } else {
            // Redirect to the error page
            header("Location: $errorPage?error=mail_error");
            exit();
        }
    } else {
        // Redirect to the error page with errors
        header("Location: $errorPage?error=validation_error");
        exit();
    }
} else {
    // Not a POST request
    header("Location: $errorPage?error=invalid_request");
    exit();
}
?>
