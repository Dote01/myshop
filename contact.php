<?php
// contact.php

// Set page title
$pageTitle = "Contact Us - MyShop";

// Include header
include('dashboard_header.php'); // Ensure you have a header.php file for consistent site layout
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you have styles.css for styling -->
    <style>
        /* styles.css */

        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background: #f5f5f5;
        }

        .container {
            width: 80%;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #007bff;
        }

        h2 {
            font-size: 2em;
            margin-top: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        label {
            font-weight: bold;
            color: #007bff;
        }

        input[type="text"], input[type="email"], textarea {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            font-size: 1em;
        }

        textarea {
            height: 150px;
            resize: vertical;
        }

        button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button[type="submit"]:hover {
            background: #0056b3;
        }

        .contact-info {
            margin-top: 30px;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .contact-info p {
            margin: 0 0 10px;
        }

        .contact-info p a {
            color: #007bff;
            text-decoration: none;
        }

        .contact-info p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <section id="contact-us">
            <div class="container">
                <h1>Contact Us</h1>

                <form action="contact_form_handler.php" method="POST">
                    <label for="name">Full Name:</label>
                    <input type="text" id="name" name="name" required>

                    <label for="email">Email Address:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="subject">Subject:</label>
                    <input type="text" id="subject" name="subject" required>

                    <label for="message">Message:</label>
                    <textarea id="message" name="message" required></textarea>

                    <button type="submit">Send Message</button>
                </form>

                <div class="contact-info">
                    <h2>Our Contact Information</h2>
                    <p>Email: <a href="mailto:support@myshop.com">support@myshop.com</a></p>
                    <p>Phone: +1 (123) 456-7890</p>
                    <p>Address: 123 MyShop Lane, Commerce City, CO 12345, USA</p>
                </div>
            </div>
        </section>
    </main>

    <!-- Include footer -->
    <?php include('dashboard_footer.php'); // Ensure you have a footer.php file for consistent site layout ?>

</body>
</html>
