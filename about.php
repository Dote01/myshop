<?php
// about.php

// Set page title
$pageTitle = "About Us - MyShop";

// Include header
include('dashboard_header.php'); // Ensure you have a header.php file for consistent site layout

// Start content
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
            width: 85%;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header.section-header {
            text-align: center;
            padding: 20px;
            background: #f4f4f4;
            border-radius: 8px;
            margin-bottom: 40px;
        }

        header.section-header h1 {
            margin: 0;
            font-size: 2.8em;
            color: #222;
            font-weight: bold;
        }

        header.section-header p {
            margin: 10px 0 0;
            font-size: 1.2em;
            color: #555;
        }

        h2 {
            color: #222;
            margin-top: 40px;
            font-size: 2.2em;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            font-weight: bold;
        }

        .team-member {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .team-member-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin-right: 20px;
            object-fit: cover;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .team-member-info h3 {
            margin: 0;
            font-size: 1.6em;
            color: #007bff;
        }

        .team-member-info p {
            margin: 5px 0 0;
            color: #555;
        }

        .why-choose-us ul, .values ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .why-choose-us ul li, .values ul li {
            background: #e9ecef;
            margin-bottom: 10px;
            padding: 15px;
            border-radius: 5px;
            font-size: 1.1em;
        }

        .why-choose-us ul li strong, .values ul li strong {
            color: #007bff;
        }

        .join-us {
            text-align: center;
            padding: 30px;
            background: #007bff;
            color: #fff;
            border-radius: 8px;
            margin-top: 40px;
        }

        .join-us h2 {
            margin-top: 0;
            font-size: 2em;
        }

        .join-us p {
            margin: 15px 0;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <!-- Main Content -->
    <main>
        <section id="about-us">
            <div class="container">
                <header class="section-header">
                    <h1>Welcome to MyShop!</h1>
                    <p>Your trusted partner in exceptional online shopping experiences.</p>
                </header>

                <section class="mission">
                    <h2>Our Mission</h2>
                    <p>Our mission is to revolutionize the online shopping experience by offering a diverse range of premium products, backed by outstanding service and support. We aim to create a seamless, enjoyable shopping journey for every customer, ensuring you always find exactly what you need.</p>
                </section>

                <section class="team">
                    <h2>Who We Are</h2>
                    <div class="team-member">
                        <img src="images/john_doe.jpg" alt="John Doe" class="team-member-img">
                        <div class="team-member-info">
                            <h3>John Doe – Founder & CEO</h3>
                            <p>With over 15 years in e-commerce and retail, John brings a wealth of knowledge and a vision for innovative online shopping solutions. His leadership and strategic insights guide MyShop’s growth and commitment to customer satisfaction.</p>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="images/jane_smith.jpg" alt="Jane Smith" class="team-member-img">
                        <div class="team-member-info">
                            <h3>Jane Smith – Chief Operating Officer</h3>
                            <p>Jane’s expertise in operations and logistics ensures that our supply chain runs smoothly and efficiently. Her focus on operational excellence helps us deliver on our promises and exceed customer expectations.</p>
                        </div>
                    </div>
                    <div class="team-member">
                        <img src="images/emily_johnson.jpg" alt="Emily Johnson" class="team-member-img">
                        <div class="team-member-info">
                            <h3>Emily Johnson – Head of Customer Experience</h3>
                            <p>Emily is dedicated to enhancing every customer’s shopping journey. Her background in customer service and user experience ensures that MyShop not only meets but surpasses the highest standards of customer care.</p>
                        </div>
                    </div>
                </section>

                <section class="why-choose-us">
                    <h2>Why Choose MyShop?</h2>
                    <ul>
                        <li><strong>Unmatched Product Selection:</strong> We offer a curated selection of high-quality products across various categories, ensuring you have access to the best items on the market.</li>
                        <li><strong>Exceptional Customer Service:</strong> Our dedicated support team is available to assist you with any inquiries or concerns, providing personalized and responsive service.</li>
                        <li><strong>Seamless Shopping Experience:</strong> Our user-friendly website and efficient checkout process make shopping with us straightforward and enjoyable.</li>
                        <li><strong>Fast & Reliable Shipping:</strong> We pride ourselves on delivering your orders promptly and securely, with tracking options to keep you informed every step of the way.</li>
                    </ul>
                </section>

                <section class="values">
                    <h2>Our Values</h2>
                    <ul>
                        <li><strong>Integrity:</strong> We operate with transparency and honesty, ensuring that every interaction and transaction reflects our core values.</li>
                        <li><strong>Excellence:</strong> Our commitment to excellence drives us to continually improve our products, services, and overall customer experience.</li>
                        <li><strong>Innovation:</strong> We embrace new technologies and trends to enhance our offerings and stay ahead in the ever-evolving e-commerce landscape.</li>
                        <li><strong>Customer-Centricity:</strong> Your satisfaction is our top priority. We listen to your feedback and strive to exceed your expectations in every aspect of our business.</li>
                    </ul>
                </section>

                <section class="join-us">
                    <h2>Join Us on Our Journey</h2>
                    <p>Explore our diverse product range and discover how MyShop can meet your shopping needs. We are excited to have you as part of our community and look forward to providing you with a top-notch shopping experience.</p>
                    <p>Thank you for choosing MyShop. Together, let’s make every shopping experience exceptional!</p>
                </section>
            </div>
        </section>
    </main>

    <!-- Include footer -->
    <?php include('dashboard_footer.php'); // Ensure you have a footer.php file for consistent site layout ?>

</body>
</html>
