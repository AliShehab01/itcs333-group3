<?php
session_start(); 

require_once 'DatabaseHelper.php';

            // Instantiate with your DB credentials
            $db = new DatabaseHelper(
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_NAME') ?: 'mydb',
                getenv('DB_USER') ?: 'user1',
                getenv('DB_PASS') ?: 'pass1'
            );

            $conn = $db->getConnection();

            // Registration process
            if (isset($_POST['register'])) {
                $full_name = $_POST['registerName'];
                $email = $_POST['registerEmail'];
                $password = password_hash($_POST['registerPassword'], PASSWORD_DEFAULT);

                // Check if it's for our uni (uob)
                $allowed_domains = ['@stu.uob.edu.bh', '@uob.edu.bh'];
                $valid_email = false;

                foreach ($allowed_domains as $domain) {
                    if (str_ends_with($email, $domain)) {
                        $valid_email = true;
                        break;
                    }
                }

                if (!$valid_email) {
                    echo "Only emails ending with @stu.uob.edu.bh or @uob.edu.bh can register.";
                    exit;
                }

                // Check if email already exists
                $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = :email");
                $checkEmail->bindParam(':email', $email);
                $checkEmail->execute();

                if ($checkEmail->rowCount() > 0) {
                    echo "<script>alert('Email already registered. Please use a different email.');</script>";
                } else {
                    $stmt = $conn->prepare("INSERT INTO users (full_name, email, password) VALUES (:full_name, :email, :password)");
                    $stmt->bindParam(':full_name', $full_name);
                    $stmt->bindParam(':email', $email);
                    $stmt->bindParam(':password', $password);

                    if ($stmt->execute()) {
                        echo "<script>alert('Registration successful! You can now log in.');</script>";
                    } else {
                        echo "<script>alert('Registration failed. Please try again.');</script>";
                    }
                }
            }

            // Login process
            if (isset($_POST['login'])) {
                $email = $_POST['signinEmail'];
                $password = $_POST['signinPassword'];

                $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = :email");
                $stmt->bindParam(':email', $email);
                $stmt->execute();

                if ($stmt->rowCount() == 1) {
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if (password_verify($password, $user['password'])) {
                        $_SESSION['user_id'] = $user['id'];
                        header("Location: indexModule2.php");
                        exit();
                    } else {
                        echo "<script>alert('Incorrect password.');</script>";
                    }
                } else {
                    echo "<script>alert('No account found with that email.');</script>";
                }
            }
            ?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign In / Register - Campus Hub</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="style.css" />
</head>

<body>
    <main>
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="index.php">
                    <img src="images/logo.png" alt="Campus Hub Logo" style="height: 50px;" />
                </a>
            </div>
        </nav>

        <!-- Auth Section -->
        <section class="container my-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <ul class="nav nav-tabs mb-3" id="authTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="signIn-tab" data-toggle="tab" href="#signIn" role="tab">Sign In</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="register-tab" data-toggle="tab" href="#register" role="tab">Register</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="authTabContent">
                        <!-- Sign In Form -->
                        <div class="tab-pane fade show active" id="signIn" role="tabpanel">
                            <form method="POST" action="login.php">
                                <input type="hidden" name="login" value="1" />
                                <div class="form-group">
                                    <label for="signinEmail">Email</label>
                                    <input type="email" name="signinEmail" class="form-control" id="signinEmail" placeholder="Enter your email" required />
                                </div>
                                <div class="form-group">
                                    <label for="signinPassword">Password</label>
                                    <input type="password" name="signinPassword" class="form-control" id="signinPassword" placeholder="Enter your password" required />
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Sign In</button>
                            </form>

                        </div>

                        <!-- Register Form -->
                        <div class="tab-pane fade" id="register" role="tabpanel">
                            <form method="POST" action="login.php">
                                <input type="hidden" name="register" value="1" />
                                <div class="form-group">
                                    <label for="registerName">Full Name</label>
                                    <input type="text" name="registerName" class="form-control" id="registerName" placeholder="Enter your full name" required />
                                </div>
                                <div class="form-group">
                                    <label for="registerEmail">Email</label>
                                    <input type="email" name="registerEmail" class="form-control" id="registerEmail" placeholder="Enter your email" required />
                                </div>
                                <div class="form-group">
                                    <label for="registerPassword">Password</label>
                                    <input type="password" name="registerPassword" class="form-control" id="registerPassword" placeholder="Create a password" required />
                                </div>
                                <button type="submit" class="btn btn-success btn-block">Register</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light pt-4 pb-3">
        <div class="container text-center">
            <small>&copy; 2025 UOB Campus Hub. All rights reserved.</small>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
</body>

</html>