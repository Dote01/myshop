<?php
// terms.php

// Set page title
$pageTitle = "Terms of Service - MyShop";

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

        p, ul {
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
        <section id="terms-of-service">
            <div class="container">
                <h1>Terms of Service</h1>

                <p>Welcome to MyShop! By using our website and services, you agree to the following terms and conditions. Please read them carefully.</p>

                <h2>1. Acceptance of Terms</h2>
                <p>By accessing or using our website, you agree to be bound by these Terms of Service and our Privacy Policy. If you do not agree with these terms, please do not use our services.</p>

                <h2>2. Changes to Terms</h2>
                <p>We reserve the right to modify these Terms of Service at any time. Any changes will be effective immediately upon posting on our website. Your continued use of the website after changes constitutes acceptance of the new terms.</p>

                <h2>3. User Responsibilities</h2>
                <p>As a user of our website, you agree to:</p>
                <ul>
                    <li>Provide accurate and complete information when creating an account or making a purchase.</li>
                    <li>Maintain the confidentiality of your account credentials and notify us of any unauthorized use.</li>
                    <li>Use our services in compliance with applicable laws and regulations.</li>
                    <li>Not engage in any activity that could harm, disable, or interfere with the website or its users.</li>
                </ul>

                <h2>4. Intellectual Property</h2>
                <p>All content, trademarks, and other intellectual property on our website are owned by or licensed to MyShop. You may not use, reproduce, or distribute any content without our prior written consent.</p>

                <h2>5. Limitation of Liability</h2>
                <p>To the fullest extent permitted by law, MyShop shall not be liable for any direct, indirect, incidental, special, or consequential damages arising from the use or inability to use our website or services.</p>

                <h2>6. Indemnification</h2>
                <p>You agree to indemnify and hold harmless MyShop, its affiliates, and their respective officers, directors, employees, and agents from any claims, liabilities, damages, losses, or expenses arising from your use of the website or violation of these Terms of Service.</p>

                <h2>7. Termination</h2>
                <p>We reserve the right to terminate or suspend your access to our website and services at our sole discretion, without notice, for any reason, including if we believe you have violated these Terms of Service.</p>

                <h2>8. Governing Law</h2>
                <p>These Terms of Service shall be governed by and construed in accordance with the laws of the state or country in which MyShop operates, without regard to its conflict of laws principles.</p>

                <h2>9. Contact Us</h2>
                <p>If you have any questions or concerns about these Terms of Service, please contact us at:</p>
                <div class="contact-info">
                    <p>Email: <a href="mailto:support@myshop.com" style="color: #fff;">support@myshop.com</a></p>
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
