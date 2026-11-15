<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Safeguard360 Football - Digital Safeguarding Compliance Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-green: #13DEB9;
            --secondary-green: #20c997;
            --light-green: #e6faf5;
            --dark-text: #2a2a2a;
            --light-text: #6c757d;
            --white: #ffffff;
        }
        
        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--dark-text);
            line-height: 1.6;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .btn-primary {
            background-color: var(--primary-green) !important;
            border-color: var(--primary-green) !important;
            color: #fff;
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
        }
        
        .btn-primary:hover, .btn-primary:focus {
            background-color: var(--secondary-green) !important;
            border-color: var(--secondary-green) !important;
            box-shadow: 0 4px 12px rgba(19, 222, 185, 0.3);
        }
        
        .btn-outline-primary {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }
        
        .navbar {
            padding: 1rem 0;
            background-color: var(--white);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.75rem;
            color: var(--dark-text) !important;
        }
        
        .navbar-brand span {
            color: var(--primary-green);
        }
        
        .hero {
            padding: 5rem 0;
            background: linear-gradient(135deg, rgba(19, 222, 185, 0.05) 0%, rgba(255, 255, 255, 1) 100%);
        }
        
        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 1.5rem;
            line-height: 1.1;
        }
        
        .hero p {
            font-size: 1.25rem;
            color: var(--light-text);
            margin-bottom: 2rem;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-title h2 {
            font-size: 2.5rem;
            position: relative;
            display: inline-block;
            padding-bottom: 1rem;
        }
        
        .section-title h2:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background-color: var(--primary-green);
            border-radius: 2px;
        }
        
        .feature-card {
            padding: 2rem;
            border-radius: 1rem;
            background-color: var(--white);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            height: 100%;
            transition: transform 0.3s ease;
            border-top: 4px solid transparent;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            border-top-color: var(--primary-green);
        }
        
        .feature-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background-color: var(--light-green);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            color: var(--primary-green);
            font-size: 1.75rem;
        }
        
        .highlight-box {
            background-color: var(--light-green);
            border-radius: 1rem;
            padding: 3rem;
            margin: 3rem 0;
        }
        
        .highlight-box h3 {
            color: var(--primary-green);
        }
        
        .problem-section {
            background-color: #f8f9fa;
            padding: 5rem 0;
        }
        
        .solution-section {
            padding: 5rem 0;
        }
        
        .market-list {
            list-style: none;
            padding-left: 0;
        }
        
        .market-list li {
            margin-bottom: 0.75rem;
            padding-left: 1.75rem;
            position: relative;
        }
        
        .market-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: var(--primary-green);
            font-weight: bold;
        }
        
        .stats-box {
            text-align: center;
            padding: 2rem;
            border-radius: 1rem;
            background-color: var(--white);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }
        
        .stats-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }
        
        footer {
            background-color: #f8f9fa;
            padding: 4rem 0 2rem;
            margin-top: 5rem;
        }
        
        .footer-links h5 {
            margin-bottom: 1.5rem;
            font-size: 1.25rem;
        }
        
        .footer-links ul {
            list-style: none;
            padding-left: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: var(--light-text);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .footer-links a:hover {
            color: var(--primary-green);
        }
        
        .copyright {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid #dee2e6;
            color: var(--light-text);
        }
        
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .hero {
                padding: 3rem 0;
            }
            
            .section-title h2 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-check me-2"></i>
                <span>Safeguard</span>360 Football
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#problem">The Problem</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#solution">The Solution</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#market">Market</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#impact">Impact</a>
                    </li>
                    <li class="nav-item ms-lg-2 mt-2 mt-lg-0">
                        <a class="btn btn-primary" href="#contact">Get Started</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1>A Digital Safeguarding Compliance Platform for Grassroots Football</h1>
                    <p class="lead">Move safeguarding from a one-off course to an embedded operational standard with our comprehensive digital platform for education, certification, monitoring, and reporting.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#solution" class="btn btn-primary btn-lg">Explore Our Solution</a>
                        <a href="#contact" class="btn btn-outline-primary btn-lg">Request a Demo</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mt-5 mt-lg-0">
                        <img src="https://images.unsplash.com/photo-1575361204480-aadea25e6e68?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Safeguard360 Football Platform" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section id="problem" class="problem-section">
        <div class="container">
            <div class="section-title">
                <h2>The Problem</h2>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="highlight-box">
                        <p class="lead">Grassroots football globally faces persistent safeguarding failures driven by:</p>
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <ul class="market-list">
                                    <li>Inconsistent training standards</li>
                                    <li>Limited oversight mechanisms</li>
                                    <li>Poor record keeping</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="market-list">
                                    <li>Weak reporting pathways</li>
                                    <li>No unified, scalable verification system</li>
                                    <li>High risk exposure for all stakeholders</li>
                                </ul>
                            </div>
                        </div>
                        <p class="mt-4">This creates significant risk exposure for governing bodies, clubs, parents, and funders who lack reliable tools to ensure consistent safeguarding compliance across the sport.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section id="solution" class="solution-section">
        <div class="container">
            <div class="section-title">
                <h2>The Solution</h2>
            </div>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h3>Safeguard360 Football</h3>
                    <p>A digital safeguarding education and compliance platform purpose-built for grassroots football. We deliver accredited online safeguarding education combined with real-time certification, monitoring, and reporting infrastructure.</p>
                    <div class="mt-4">
                        <div class="d-flex align-items-start mb-3">
                            <div class="feature-icon me-3">
                                <i class="bi bi-laptop"></i>
                            </div>
                            <div>
                                <h5>Web Platform & Mobile App</h5>
                                <p>Accessible tools for all stakeholders in grassroots football.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="feature-icon me-3">
                                <i class="bi bi-award"></i>
                            </div>
                            <div>
                                <h5>FIFA-Aligned Courses</h5>
                                <p>Modular safeguarding courses aligned with FIFA Guidance and best practices.</p>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="feature-icon me-3">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h5>Compliance Infrastructure</h5>
                                <p>Club, league, and federation-level dashboards with real-time monitoring.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mt-5 mt-lg-0">
                        <img src="https://images.unsplash.com/photo-1551958219-acbc608c6377?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Football Safeguarding Solution" class="img-fluid rounded shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="section-title">
                <h2>Platform Features</h2>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-mortarboard"></i>
                        </div>
                        <h4>Role-Specific Training</h4>
                        <p>Customized pathways for coaches, referees, volunteers, and safeguarding officers with content tailored to each role's responsibilities.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <h4>Digital Verification</h4>
                        <p>QR-enabled certificates with expiry tracking to reduce fraud and ensure certifications are always current and verifiable.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-graph-up"></i>
                        </div>
                        <h4>Compliance Dashboards</h4>
                        <p>Real-time monitoring at club, league, and federation levels with automated reporting and audit trails.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-megaphone"></i>
                        </div>
                        <h4>Secure Reporting</h4>
                        <p>Confidential incident reporting and escalation tools with secure pathways to appropriate authorities.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-bell"></i>
                        </div>
                        <h4>Automated Reminders</h4>
                        <p>Timely renewal notifications and compliance alerts to ensure no certification lapses unnoticed.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi bi-database"></i>
                        </div>
                        <h4>Data Intelligence</h4>
                        <p>Analytics and insights to support policy development, funding applications, and governance improvements.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Market Section -->
    <section id="market" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Target Market</h2>
            </div>
            <div class="row">
                <div class="col-lg-6">
                    <h4>Primary Users</h4>
                    <ul class="market-list">
                        <li>Grassroots coaches and referees</li>
                        <li>Club officials and safeguarding officers</li>
                        <li>Volunteers and team managers</li>
                        <li>Parents and guardians</li>
                    </ul>
                </div>
                <div class="col-lg-6">
                    <h4>Institutional Clients</h4>
                    <ul class="market-list">
                        <li>Grassroots leagues and associations</li>
                        <li>Football academies and clubs</li>
                        <li>Schools and colleges with football programs</li>
                        <li>National and regional football associations</li>
                        <li>NGOs delivering sport for development programs</li>
                    </ul>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12">
                    <div class="highlight-box">
                        <h3 class="text-center">Initial Markets</h3>
                        <div class="row text-center mt-4">
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <div class="stats-number">UK</div>
                                    <h5>Established Football Market</h5>
                                    <p>Strong grassroots infrastructure with clear regulatory requirements.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stats-box">
                                    <div class="stats-number">Nigeria</div>
                                    <h5>Emerging Football Power</h5>
                                    <p>Rapidly growing grassroots participation with increasing focus on safeguarding standards.</p>
                                </div>
                            </div>
                        </div>
                        <p class="text-center mt-4">With clear scalability across Africa and other emerging football regions with similar safeguarding challenges.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Competitive Advantage -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="section-title">
                <h2>Competitive Advantage</h2>
            </div>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="bi bi-gear"></i>
                                </div>
                                <h5>Football-Specific Design</h5>
                                <p>Built exclusively for grassroots football with workflows that match the sport's unique ecosystem.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <h5>Expert Governance</h5>
                                <p>Developed and overseen by FIFA Guidance Safeguarding Platinum holder with deep domain expertise.</p>
                            </div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="feature-icon mx-auto mb-3">
                                    <i class="bi bi-building"></i>
                                </div>
                                <h5>Federation-Ready Architecture</h5>
                                <p>Scalable platform designed for integration with national and regional football associations.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section id="impact" class="py-5">
        <div class="container">
            <div class="section-title">
                <h2>Impact & Vision</h2>
            </div>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div class="highlight-box">
                        <h3 class="text-center">Our Vision</h3>
                        <p class="text-center lead">To become the global safeguarding compliance infrastructure for grassroots sport, combining education, verification, reporting, and data intelligence into one trusted platform.</p>
                        
                        <div class="row mt-5">
                            <div class="col-md-6">
                                <h5>Immediate Impact</h5>
                                <ul class="market-list">
                                    <li>Improved safeguarding literacy and practice</li>
                                    <li>Reduced incidents through early awareness</li>
                                    <li>Stronger compliance for clubs and leagues</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Long-Term Vision</h5>
                                <ul class="market-list">
                                    <li>Evidence-based policy development</li>
                                    <li>Increased confidence for parents and funders</li>
                                    <li>Expansion beyond football to other youth sports</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section id="contact" class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2>Ready to Transform Safeguarding in Grassroots Football?</h2>
                    <p class="lead mb-4">Join clubs, leagues, and associations already using Safeguard360 to build safer football environments.</p>
                    <div class="d-flex flex-column flex-md-row justify-content-center gap-3">
                        <a href="#" class="btn btn-primary btn-lg px-5">Request a Demo</a>
                        <a href="#" class="btn btn-outline-primary btn-lg px-5">Contact Our Team</a>
                    </div>
                    <p class="mt-4 text-muted">Trust and credibility are treated as core assets, not marketing claims.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <h4 class="mb-3">
                        <i class="bi bi-shield-check me-2"></i>
                        <span>Safeguard</span>360 Football
                    </h4>
                    <p>A Digital Safeguarding Compliance Platform for Grassroots Football</p>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-decoration-none">
                            <i class="bi bi-twitter fs-5"></i>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="bi bi-linkedin fs-5"></i>
                        </a>
                        <a href="#" class="text-decoration-none">
                            <i class="bi bi-facebook fs-5"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Platform</h5>
                        <ul>
                            <li><a href="#features">Features</a></li>
                            <li><a href="#solution">How It Works</a></li>
                            <li><a href="#market">Pricing</a></li>
                            <li><a href="#">Case Studies</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Resources</h5>
                        <ul>
                            <li><a href="#">Documentation</a></li>
                            <li><a href="#">Safeguarding Guides</a></li>
                            <li><a href="#">FAQ</a></li>
                            <li><a href="#">Support</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <div class="footer-links">
                        <h5>Company</h5>
                        <ul>
                            <li><a href="#">About Us</a></li>
                            <li><a href="#">Advisory Board</a></li>
                            <li><a href="#">Careers</a></li>
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="footer-links">
                        <h5>Legal</h5>
                        <ul>
                            <li><a href="#">Privacy Policy</a></li>
                            <li><a href="#">GDPR Compliance</a></li>
                            <li><a href="#">Terms of Service</a></li>
                            <li><a href="#">Cookie Policy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2023 Safeguard360 Football. All rights reserved. Designed with <i class="bi bi-heart-fill text-danger"></i> for safer grassroots football.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Navbar background on scroll
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.1)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.05)';
            }
        });
    </script>
</body>
</html>