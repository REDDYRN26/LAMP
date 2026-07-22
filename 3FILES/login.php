<?php
require_once 'config.php';

// If already logged in, redirect to home
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';

if (isset($_POST['login'])) {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = login_user($email, $password);

    if ($result['success']) {
        set_message($result['message'], 'success');
        redirect('index.php');
    } else {
        $error = $result['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require_once 'header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Logo/Title -->
                        <div class="text-center mb-4">
                            <i class="fas fa-coffee text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold mt-2">Welcome to <?php echo SITE_NAME; ?></h3>
                            <p class="text-muted">Login to your account</p>
                        </div>

                        <!-- Error Message -->
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="email" 
                                       name="email" placeholder="Enter your email" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-bold">Password</label>
                                <input type="password" class="form-control form-control-lg" id="password" 
                                       name="password" placeholder="Enter your password" required>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="remember_me">
                                <label class="form-check-label" for="remember_me">
                                    Remember me
                                </label>
                            </div>

                            <button type="submit" name="login" class="btn btn-warning btn-lg w-100 fw-bold">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </form>

                        <hr>

                        <!-- Links -->
                        <div class="text-center mb-3">
                            <p class="text-muted small mb-0">Don't have an account?</p>
                            <a href="register.php" class="text-warning fw-bold">Create one here</a>
                        </div>

                        <div class="text-center">
                            <a href="#" class="text-decoration-none text-muted small">Forgot password?</a>
                        </div>
                    </div>
                </div>

                <!-- Demo Credentials -->
                <div class="alert alert-info mt-4" role="alert">
                    <strong>Demo Account:</strong>
                    <br>Email: demo@example.com
                    <br>Password: password123
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
