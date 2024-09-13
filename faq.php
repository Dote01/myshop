<?php include 'dashboard_header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Frequently Asked Questions</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include external CSS for better organization -->
    <style>
        /* General Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f6f7;
            margin: 0;
            padding: 0;
            color: #333;
        }

        /* Header Styles */
        header {
            background: linear-gradient(to right, #28a745, #218838);
            color: #fff;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo img {
            height: 60px;
            transition: transform 0.3s;
        }

        .logo img:hover {
            transform: scale(1.1);
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 12px 20px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        nav ul li a:hover {
            background-color: #218838;
            color: #fff;
        }

        /* FAQ Page Styles */
        .faq-container {
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin: 20px;
        }

        .faq-container h2 {
            margin-bottom: 20px;
            color: #28a745;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-item h3 {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            margin: 0;
            transition: background-color 0.3s;
        }

        .faq-item h3:hover {
            background-color: #ced4da;
        }

        .faq-item p {
            display: none;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 8px;
            margin: 0;
        }
    </style>
</head>
<body>
    <!-- FAQ Page Content -->
    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>

        <div class="faq-item">
            <h3>What is this platform about?</h3>
            <p>This platform is designed to connect buyers and retailers, providing a seamless experience for managing orders, stock, and customer interactions.</p>
        </div>

        <div class="faq-item">
            <h3>How do I create an account?</h3>
            <p>To create an account, click on the sign-up button on the homepage and fill out the required information. You will receive a confirmation email to verify your account.</p>
        </div>

        <div class="faq-item">
            <h3>How can I contact customer support?</h3>
            <p>You can contact customer support by visiting the 'Contact Us' page or sending an email to support@example.com. We are available 24/7 to assist you.</p>
        </div>

        <div class="faq-item">
            <h3>What payment methods are accepted?</h3>
            <p>We accept various payment methods including credit/debit cards, PayPal, and bank transfers. Please check the payment section during checkout for available options.</p>
        </div>

        <div class="faq-item">
            <h3>How do I reset my password?</h3>
            <p>If you forgot your password, click on the 'Forgot Password' link on the login page. Follow the instructions to reset your password via email.</p>
        </div>
    </div>

    <script>
        // Toggle FAQ answers
        document.querySelectorAll('.faq-item h3').forEach(header => {
            header.addEventListener('click', () => {
                const answer = header.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
</body>
</html>
