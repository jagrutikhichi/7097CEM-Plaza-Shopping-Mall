<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | Mall Shopping Portal</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">

    <style>
        /* Body Styling */
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
        }

        /* Transparent Container */
        .content-container {
            flex: 1;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 90%;
            margin: 30px auto;
        }

        /* Navbar */
        .navbar {
            background: #000;
            padding: 15px;
            position: sticky;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }

        .navbar-brand {
            font-size: 28px;
            font-weight: bold;
            display: flex;
            align-items: center;
            color: white !important;
        }

        .navbar-brand img {
            height: 50px;
            margin-right: 12px;
        }

        .navbar-nav .nav-link {
            font-size: 18px;
            font-weight: 500;
            padding: 8px 12px;
            color: white !important;
        }

        .navbar-nav .nav-link:hover {
            color: #f8d210 !important;
        }

        /* Highlight Text */
        .highlight-text {
            text-align: center;
            background: #ffcc00;
            color: #000;
            font-size: 22px;
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
        }

        /* Contact Section */
        .contact-details {
            text-align: center;
            font-size: 20px;
            font-weight: 500;
            margin-bottom: 20px;
        }

        .contact-section {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            gap: 20px;
            padding: 20px;
        }

        .contact-info, .contact-form, .faq-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 3px 3px 10px rgba(0, 0, 0, 0.1);
            flex: 1;
            min-width: 300px;
        }

        .contact-info h3, .contact-form h3, .faq-section h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .contact-info p {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .contact-form form input, .contact-form form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .contact-form form button {
            background: #ff6600;
            color: white;
            font-weight: 600;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease-in-out;
        }

        .contact-form form button:hover {
            background: #cc5500;
        }

        /* FAQ Section */
        .faq-item {
            margin-bottom: 15px;
        }

        .faq-item h5 {
            font-weight: bold;
            cursor: pointer;
        }

        .faq-item p {
            display: none;
            font-size: 16px;
            padding: 5px;
        }

        /* Footer */
        .footer {
            background: #000;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 14px;
            margin-top: auto;
        }

    </style>
</head>
<body>

<!-- 🔹 Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="uploads/logo.png" alt="Mall Logo"> Plaza Shoppings
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about_us.php">ℹ️ About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_us.php">📞 Contact</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Highlight Text -->
<div class="highlight-text">📞 Get in Touch with Us 📞</div>

<!-- 🔹 Contact Section -->
<div class="content-container">
    <div class="container">
        <div class="contact-details">
            <p>Email: support@shoppingmall.com</p>
            <p>Phone: +123 456 789</p>
        </div>

        <div class="contact-section">
            <!-- Contact Info -->
            <div class="contact-info">
                <h3>📍 Mall Address</h3>
                <p>Main Street, City, Country</p>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126743.04335751793!2d-74.0060152!3d40.7127761!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x7b5b4b7bcbdfebc!2sMall!5e0!3m2!1sen!2sus!4v1630000000000"
                    width="100%" height="200" style="border:0;" allowfullscreen="" loading="lazy">
                </iframe>
            </div>

            <!-- Contact Form -->
            <div class="contact-form">
                <h3>📩 Contact Form</h3>
                <form>
                    <input type="text" placeholder="Your Name" required>
                    <input type="email" placeholder="Your Email" required>
                    <textarea placeholder="Your Message" rows="4" required></textarea>
                    <button type="submit">Send Message</button>
                </form>
            </div>

            <!-- FAQs -->
            <div class="faq-section">
                <h3>❓ Frequently Asked Questions</h3>
                <div class="faq-item">
                    <h5>How can I track my order?</h5>
                    <p>Log in to your account and go to the 'Orders' section to track your order.</p>
                </div>
                <div class="faq-item">
                    <h5>What is the return policy?</h5>
                    <p>You can return items within 30 days of purchase.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔹 Footer -->
<footer class="footer">© 2025 Mall Shopping Portal. All Rights Reserved.</footer>

<script>
    $(".faq-item h5").click(function() {
        $(this).next("p").slideToggle();
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
