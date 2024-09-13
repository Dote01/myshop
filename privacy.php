<?php
// privacy.php

// Set page title
$pageTitle = "Privacy Policy - MyShop";

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

        h1, h2, h3 {
            color: #222;
        }

        h1 {
            font-size: 2.5em;
            margin-bottom: 20px;
        }

        h2 {
            font-size: 2em;
            margin-top: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        h3 {
            font-size: 1.5em;
            margin-top: 20px;
        }

        p {
            margin: 0 0 20px;
        }

        ul {
            list-style-type: disc;
            margin: 0 0 20px 20px;
        }

        .contact-info {
            background: #007bff;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
            text-align: center;
        }

        .contact-info p {
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <section id="privacy-policy">
            <div class="container">
                <h1>Privacy Policy</h1>

                <p>Welcome to MyShop! This Privacy Policy outlines how we collect, use, and protect your personal information when you visit our website and use our services. Your privacy is important to us, and we are committed to safeguarding your data.</p>

                <h2>1. Information We Collect</h2>
                <p>We collect various types of information to provide and improve our services, including:</p>
                <ul>
                    <li><strong>Personal Information:</strong> Name, email address, phone number, etc., that you provide when creating an account or making a purchase.</li>
                    <li><strong>Usage Data:</strong> Information about how you interact with our website, including IP address, browser type, pages visited, and the time spent on those pages.</li>
                    <li><strong>Cookies and Tracking Technologies:</strong> We use cookies and similar technologies to track your activity on our website and enhance your user experience.</li>
                </ul>

                <h2>2. How We Use Your Information</h2>
                <p>Your information is used for various purposes, including:</p>
                <ul>
                    <li>Processing transactions and managing your account.</li>
                    <li>Improving our website and services based on user feedback and usage patterns.</li>
                    <li>Sending promotional materials and updates, if you have opted to receive them.</li>
                    <li>Responding to your inquiries and providing customer support.</li>
                </ul>

                <h2>3. Cookies and Tracking Technologies</h2>
                <p>We use cookies and other tracking technologies to enhance your browsing experience. You can control and manage cookies through your browser settings. However, disabling cookies may affect the functionality of our website.</p>

                <h2>4. Third-Party Services</h2>
                <p>We may use third-party services, such as analytics tools and payment processors, to help us operate and improve our services. These third parties may have access to your information as necessary to perform their functions but are obligated to protect your data and use it only for the intended purposes.</p>

                <h2>5. Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. However, no method of transmission over the internet or electronic storage is 100% secure, so we cannot guarantee absolute security.</p>

                <h2>6. User Rights</h2>
                <p>You have the right to access, correct, or delete your personal information. If you wish to exercise these rights or have any concerns about how your data is handled, please contact us using the details provided below.</p>

                <h2>7. Changes to This Privacy Policy</h2>
                <p>We may update this Privacy Policy from time to time to reflect changes in our practices or legal requirements. Any changes will be posted on this page with an updated effective date.</p>

                <h2>8. Contact Us</h2>
                <p>If you have any questions or concerns about this Privacy Policy or how we handle your personal information, please contact us at:</p>
                <div class="contact-info">
                    <p>Email: <a href="mailto:support@myshop.com" style="color: #fff;">support@myshop.com</a></p>
                    <p>Phone: +1 (123) 456
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
    