<?php
require_once 'config.php';

// If already logged in, redirect to home
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
$success = '';

if (isset($_POST['register'])) {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $address = $_POST['address'] ?? '';
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    // Validate passwords match
    if ($password !== $password_confirm) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters';
    } else {
        $result = register_user($name, $email, $password, $phone, $address);
        if ($result['success']) {
            $success = $result['message'];
            // Clear form
            $name = $email = $phone = $address = '';
        } else {
            $error = $result['message'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php require_once 'header.php'; ?>

    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <!-- Logo/Title -->
                        <div class="text-center mb-4">
                            <i class="fas fa-coffee text-warning" style="font-size: 2.5rem;"></i>
                            <h3 class="fw-bold mt-2">Join <?php echo SITE_NAME; ?></h3>
                            <p class="text-muted">Create your account to start ordering</p>
                        </div>

                        <!-- Error Message -->
                        <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <!-- Success Message -->
                        <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                            <p class="mt-2 mb-0">
                                <a href="login.php" class="alert-link">Click here to login</a>
                            </p>
                        </div>
                        <?php endif; ?>

                        <!-- Register Form -->
                        <form method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label fw-bold">Full Name</label>
                                <input type="text" class="form-control form-control-lg" id="name" 
                                       name="name" placeholder="John Doe" value="<?php echo htmlspecialchars($name); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-bold">Email Address</label>
                                <input type="email" class="form-control form-control-lg" id="email" 
                                       name="email" placeholder="you@example.com" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-bold">Phone Number</label>
                                <input type="tel" class="form-control form-control-lg" id="phone" 
                                       name="phone" placeholder="+1 (555) 123-4567" value="<?php echo htmlspecialchars($phone); ?>">
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control form-control-lg" id="address" 
                                          name="address" rows="2" placeholder="123 Main Street, Apt 4B..."><?php echo htmlspecialchars($address); ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password" class="form-label fw-bold">Password</label>
                                        <input type="password" class="form-control form-control-lg" id="password" 
                                               name="password" placeholder="At least 6 characters" required>
                                        <small class="text-muted">Min. 6 characters</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirm" class="form-label fw-bold">Confirm Password</label>
                                        <input type="password" class="form-control form-control-lg" id="password_confirm" 
                                               name="password_confirm" placeholder="Confirm password" required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" class="text-decoration-none">Terms of Service</a> and 
                                    <a href="#" class="text-decoration-none">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit" name="register" class="btn btn-warning btn-lg w-100 fw-bold">
                                <i class="fas fa-user-plus"></i> Create Account
                            </button>
                        </form>

                        <hr>

                        <!-- Links -->
                        <div class="text-center">
                            <p class="text-muted small mb-0">Already have an account?</p>
                            <a href="login.php" class="text-warning fw-bold">Login here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
