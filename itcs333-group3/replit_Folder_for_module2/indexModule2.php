<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

    <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Campus Hub - Main Page</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
    <script src="script2.js" defer></script>
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
                        <li class="nav-item"><a class="nav-link" href="#">Events</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule2.php">Study Groups</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule3.html">Course Reviews</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule5.html">Notes</a></li>
                        <li class="nav-item"><a class="nav-link" href="#">News</a></li>
                        <li class="nav-item"><a class="nav-link" href="indexModule7.html">Student Marketplace</a></li>
                    </ul>
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



        <section class="container mt-5">
            <div class="row align-items-start">
                <!-- Left column -->
                <div class="col-md-5">
                    <h3 class="fw-bold text-dark" id="mov1">Looking for a study group? This is your right place
                        for
                        that!</h3>
                </div>


                <!-- Divider -->
                <div class="col-md-1 d-flex justify-content-center">
                    <div style="width: 1px; height: 100%; background-color: #ccc;"></div>
                </div>

                <!-- Right column -->
                <div class="col-md-6">
                    <p class="text-muted">Create or join a study group that fits your needs — subject, level,
                        and more.
                    </p>
                </div>
            </div>
        </section> <!--Search side-->
        <section class="container mt-5">
            <div class="col-md-8">
                <input type="search" class="form-control form-control-lg" id="searchInput"
                    placeholder="🔍Type subject id and the section, e.g.: ITCS333 3">
            </div>
            </br>
            <div id="resultSearch"> </div>

        </section>

        <section class="container mt-5"> <!--your main work on module is in this part -->
            <!-- Add Group Form -->
            <div class="card  p-4 mb-5">
                <h5 class="mb-3">Add a New Study Group</h5>
                <form id="groupForm">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label>Subject Code</label>
                            <input required type="text" id="subject" class="form-control" placeholder="ITCS333" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="section">Section</label>
                            <input required type="text" id="section" class="form-control" placeholder="3" />
                        </div>
                        <div class="form-group col-md-4">
                            <label for="college">College</label>
                            <select required id="college" class="form-control">
                                <option disabled selected value="">Select College</option>
                                <option>College of Information Technology</option>
                                <option>College of Applied Studies</option>
                                <option>College of Arts</option>
                                <option>Bahrain Teachers College</option>
                                <option>College of Business</option>
                                <option>College of Engineering</option>
                                <option>College of Health and Sport Sciences</option>
                                <option>College of Science</option>
                                <option>College of Law</option>
                            </select>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="whatsapp">WhatsApp Group Link</label>
                        <input required type="url" class="form-control" placeholder="https://chat.whatsapp.com/..." />
                    </div>
                    <button type="submit" class="btn btn-primary">Add Group</button>
                </form>
        </section>



        <nav> <!--useful for your modules-->
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
                        <li><a href="https://www.uob.edu.bh/events/" class="text-light" target="_blank"
                                rel="noopener noreferrer">Events</a></li>
                        <li><a href="https://www.uob.edu.bh/faq-items/" class="text-light" target="_blank"
                                rel="noopener noreferrer">FAQ</a></li>
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
                    <a href="https://www.instagram.com/uobedubh/?hl=en" class="d-block text-light" target="_blank"
                        rel="noopener noreferrer">Instagram</a>
                    <a href="https://x.com/uobedubh?lang=en" class="d-block text-light" target="_blank"
                        rel="noopener noreferrer">X (Twitter)</a>
                    <a href="https://www.facebook.com/MyUOB/" class="d-block text-light" target="_blank"
                        rel="noopener noreferrer">Facebook</a>
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