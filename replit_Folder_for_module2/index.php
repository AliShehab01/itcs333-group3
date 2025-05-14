<?php
session_start();
?>  <!--added for reg/sign-->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Campus - University Of Bahrain</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        crossorigin="anonymous" />

    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <main>
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.png" alt="Campus Hub Logo" style="height: 50px;" />
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-end" id="navbarNav">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule8.html">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule2.php" id="cksign">Study Groups</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule3.html">Course Reviews</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule5.html">Notes</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule7.html">Student Marketplace</a></li>
                    </ul>
                    <?php if (isset($_SESSION['user_id'])): ?> 
                        <li class="nav-item">
                            <a class="nav-link btn btn-outline-danger ml-2"   href="logout.php">Sign Out</a>
                        </li>
                    <?php endif; ?>
                </div>
            </div>
        </nav>

        <section id="backgroundImg">
            <div class="container">
                <h1 id="firsth1">Welcome to Campus Hub</h1>
                <p class="lead">
                    Your one-stop platform for events, study groups, course reviews & more at University of Bahrain!
                </p>
            </div>
        </section>
        <section class="container my-5">
            <div class="row">
                <div class="col-md-8 mb-2">
                    <input type="search" class="form-control" placeholder="Search for study groups, courses, events..."
                        id="search">
                </div>
                <div class="col-md-4 mb-2">
                    <select class="form-control" id="filter">
                        <option>Filter by...</option>
                        <option>Study Groups</option>
                        <option>Events</option>
                        <option>Course Reviews</option>
                        <option>Student Marketplace</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="container text-center my-5">
            <h2 class="mb-4">Explore Campus Life</h2>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Find Study Groups</h5>
                            <p class="card-text">Connect with peers in your course and improve together.</p>
                            <a href="indexModule2.php" class="btn btn-primary" id="cksign">Get Started</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">News</h5>
                            <p class="card-text">Stay updated on workshops, fairs, and student activities.</p>
                            <a href="indexModule8" class="btn btn-primary">View Events</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h5 class="card-title">Course Reviews</h5>
                            <p class="card-text">See what other students think of courses and professors.</p>
                            <a href="indexModule3.html" class="btn btn-primary">Browse Reviews</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Student Marketplace</h5>
                            <p class="card-text">Buy or sell textbooks for your courses</p>
                            <a href="indexModule7.html" class="btn btn-primary">Browse Books</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="card shadow-sm h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Study Notes</h5>
                            <p class="card-text">Find and share study notes for your courses</p>
                            <a href="indexModule5.html" class="btn btn-primary">View Notes</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="container text-center mb-5">
            <h3>Visit University of Bahrain</h3>
            <a href="https://maps.app.goo.gl/aZHb1XkvWgUzofJ49" target="_blank" class="btn btn-outline-secondary mt-2">
                View Map of Sakhir Campus
            </a>
        </section>

        <nav> 
            <ul class="pagination justify-content-center">
                <li class="page-item disabled"><a class="page-link" href="#">Previous</a></li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </main>
    <!-- Footer -->
    <footer class="bg-dark text-light pt-5 pb-3">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <h5>About</h5>
                    <p>Your go-to student platform at UOB — for everything from study groups to campus events!</p>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="https://www.uob.edu.bh/events/" class="text-light" target="_blank" rel="noopener noreferrer">Events</a></li>
                        <li><a href="https://www.uob.edu.bh/faq-items/" class="text-light" target="_blank" rel="noopener noreferrer">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Contact</h5>
                    <ul class="list-unstyled">
                        <li><a href="mailto:studentcc@uob.edu.bh" class="text-light">Email Us</a></li>
                        <li><a href="tel:+17438888" class="text-light">Call Us</a></li>
                    </ul>
                </div>
                <div class="col-md-3 mb-4">
                    <h5>Follow</h5>
                    <a href="https://www.instagram.com/uobedubh/?hl=en" class="d-block text-light" target="_blank" rel="noopener noreferrer">Instagram</a>
                    <a href="https://x.com/uobedubh?lang=en" class="d-block text-light" target="_blank" rel="noopener noreferrer">X (Twitter)</a>
                    <a href="https://www.facebook.com/MyUOB/" class="d-block text-light" target="_blank" rel="noopener noreferrer">Facebook</a>
                </div>
            </div>
            <hr class="bg-light" />
            <div class="text-center">
                <small>&copy; 2025 UOB Campus Hub. All rights reserved.</small>
            </div>
        </div>
    </footer>

</body>

</html>
