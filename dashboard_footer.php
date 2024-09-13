<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Footer</title>
    <style>
        footer {
            background-color: #2e2e2e;
            color: #fff;
            padding: 20px 0;
            text-align: center;
            margin-top: auto;
        }
        
        .footer-content {
            max-width: 1200px;
            margin: auto;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }
        
        .footer-nav {
            flex: 1;
            min-width: 200px;
            text-align: left;
        }
        
        .footer-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .footer-nav ul li {
            margin: 8px 0;
        }
        
        .footer-nav ul li a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-nav ul li a:hover {
            color: #4CAF50;
        }
        
        .footer-social {
            flex: 1;
            min-width: 200px;
            text-align: center;
            margin-top: 10px;
        }
        
        .footer-social a {
            margin: 0 10px;
            color: #fff;
            text-decoration: none;
        }
        
        .footer-social img {
            width: 24px;
            vertical-align: middle;
        }
        
        .footer-contact {
            flex: 1;
            min-width: 200px;
            text-align: right;
        }
        
        .footer-contact p {
            margin: 5px 0;
        }
        
        .footer-contact a {
            color: #fff;
            text-decoration: none;
        }
        
        .footer-bottom {
            border-top: 1px solid #444;
            padding-top: 10px;
            margin-top: 20px;
        }
        
        .footer-bottom p {
            margin: 0;
        }
    </style>
</head>
<body>
    <footer>
        <div class="footer-content">
            <!-- Footer Navigation -->
            <nav class="footer-nav">
                <ul>
                    <li><a href="about.php">About Us</a></li>
                    <li><a href="privacy.php">Privacy Policy</a></li>
                    <li><a href="terms.php">Terms of Service</a></li>
                    <li><a href="contact.php">Contact Us</a></li>
                </ul>
            </nav>
            
            <!-- Footer Social Media Icons -->
            <div class="footer-social">
                <a href="https://www.facebook.com/myshop" target="_blank">
                    <img src="facebook-icon.png" alt="Facebook">
                </a>
                <a href="https://www.twitter.com/myshop" target="_blank">
                    <img src="twitter-icon.png" alt="Twitter">
                </a>
                <a href="https://www.instagram.com/myshop" target="_blank">
                    <img src="instagram-icon.png" alt="Instagram">
                </a>
            </div>
            
            <!-- Footer Contact Information -->
            <div class="footer-contact">
                <p>Email: <a href="mailto:info@myshop.com">info@myshop.com</a></p>
                <p>Phone: <a href="tel:+255753961345">+255753961345</a></p>
            </div>
        </div>
        
        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p>&copy; 2024 MYSHOP. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
