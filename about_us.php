<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Plaza Shopping Mall</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="assets/styles.css">
    <style>
        /* Page Styling */
        body {
            background: url('assets/background.gif') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Container Styling */
        .content-container {
            flex: 1;
            padding: 40px 20px;
            background: rgba(255, 255, 255, 0.9);
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
            color: white !important;
            display: flex;
            align-items: center;
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
            color: #ffcc00 !important;
        }

        /* Section Styling */
        .section {
            padding: 30px 0;
        }

        .section h3 {
            text-align: center;
            color: #000;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .section p, .section ul {
            text-align: center;
            font-size: 18px;
            color: #333;
        }

        .highlight-text {
            text-align: center;
            background: #ffcc00;
            color: #000;
            font-size: 22px;
            font-weight: bold;
            padding: 10px;
            margin-bottom: 20px;
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
            <img src="uploads/logo.png" alt="Mall Logo"> Plaza Shopping Mall
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php">🏠 Home</a></li>
                <li class="nav-item"><a class="nav-link" href="about_us.php">ℹ️ About Us</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_us.php">📞 Contact</a></li>
                <li class="nav-item"><a class="nav-link" href="#">💼 Careers</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- 🔹 Highlight Text -->
<div class="highlight-text">✨ Experience Shopping Like Never Before! ✨</div>

<!-- 🔹 Main Content -->
<div class="content-container">
    <div class="container">
        <h2 class="text-center mb-4">About Plaza Shopping Mall</h2>

        <!-- Mall History & Vision -->
        <div class="section">
            <h3>🏛️ Our History & Vision</h3>
            <p>Established in [Year], Plaza Shopping Mall has been the premier shopping destination, offering a blend of retail, entertainment, and dining. Our vision is to create a world-class shopping experience that meets the evolving needs of our customers.</p>
        </div>

        <!-- Mission -->
        <div class="section">
            <h3>🎯 Our Mission</h3>
            <p>To deliver an unparalleled shopping experience with top-notch services, ensuring customer satisfaction and sustainability.</p>
        </div>

        <!-- Management Details -->
        <div class="section">
            <h3>👔 Our Management Team</h3>
            <p>Led by a team of experienced professionals, our management ensures that Plaza Shopping Mall remains at the forefront of innovation and excellence in retail.</p>
        </div>

        <!-- Services Offered -->
        <div class="section">
            <h3>🛠️ Services We Offer</h3>
            <ul class="list-unstyled">
                <li>✅ Ample Parking Space</li>
                <li>✅ Valet Parking</li>
                <li>✅ Free Wi-Fi</li>
                <li>✅ Concierge Services</li>
                <li>✅ 24/7 Security</li>
                <li>✅ Kids Play Area</li>
            </ul>
        </div>

        <!-- Sustainability Initiatives -->
        <div class="section">
            <h3>🌍 Sustainability Initiatives</h3>
            <p>We are committed to reducing our environmental footprint through energy-efficient lighting, waste recycling programs, and sustainable building practices.</p>
        </div>

        <!-- Careers -->
        <div class="section">
            <h3>💼 Careers at Plaza Shopping Mall</h3>
            <p>Looking for an exciting career in retail? <a href="careers.php">Explore our job openings</a> and be part of our amazing team!</p>
        </div>

        <!-- Contact Information -->
        <div class="section">
            <h3>📞 Contact Us</h3>
            <p>Email: info@plazamall.com</p>
            <p>Phone: +1 234 567 890</p>
            <p>Address: 123 Shopping Street, City, Country</p>
        </div>

        <!-- Virtual Tour -->
        <div class="section">
            <h3>📹 Virtual Tour</h3>
            <p>Take a tour of our mall from anywhere! <a href="virtual-tour.php">Click here for the Virtual Tour</a>.</p>
        </div>
    </div>
</div>

<!-- 🔹 Footer -->
<footer class="footer">
    <div class="container">
        <p>© 2025 Plaza Shopping Mall. All Rights Reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
