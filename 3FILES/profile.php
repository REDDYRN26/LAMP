<?php
require_once 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_message('Please login to view your profile', 'error');
    redirect('login.php');
}

$user_id = get_user_id();
global $conn;

// Get user data
$result = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $result->fetch_assoc();

// Handle profile update
if (isset($_POST['update_profile'])) {
    $name = sanitize($_POST['name']);
    $phone = sanitize($_POST['phone']);
    $address = sanitize($_POST['address']);
    $city = sanitize($_POST['city']);
    $zip_code = sanitize($_POST['zip_code']);

    if (empty($name)) {
        set_message('Name is required', 'error');
    } else {
        $update = $conn->query("
            UPDATE users SET 
            name = '$name', 
            phone = '$phone', 
            address = '$address', 
            city = '$city', 
            zip_code = '$zip_code' 
            WHERE id = $user_id
        ");

        if ($update) {
            set_message('Profile updated successfully', 'success');
            // Refresh user data
            $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
            $user = $result->fetch_assoc();
            $_SESSION['name'] = $user['name'];
        } else {
            set_message('Failed to update profile', 'error');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <!-- Page Header -->
    <section class="bg-dark text-white py-4">
        <div class="container">
            <h1 class="fw-bold">
                <i class="fas fa-user"></i> My Profile
            </h1>
        </div>
    </section>

    <!-- Profile Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm text-center">
                    <div class="card-body p-4">
                        <div class="mb-4">
                            <i class="fas fa-user-circle" style="font-size: 4rem; color: #ffc107;"></i>
                        </div>
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($user['name']); ?></h5>
                        <p class="text-muted small">
                            <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($user['email']); ?>
                        </p>
                        <p class="text-muted small">
                            <i class="fas fa-calendar"></i> Joined: <?php echo date('M d, Y', strtotime($user['created_at'])); ?>
                        </p>
                    </div>
                </div>

                <!-- Quick Stats -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-3">Account Stats</h6>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Total Orders</p>
                            <h6 class="fw-bold">
                                <?php 
                                    $orders = get_user_orders($user_id);
                                    echo count($orders);
                                ?>
                            </h6>
                        </div>
                        <div>
                            <p class="text-muted small mb-1">Account Status</p>
                            <h6 class="fw-bold"><span class="badge bg-success">Active</span></h6>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div class="col-md-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="fas fa-edit"></i> Edit Profile
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label fw-bold">Full Name</label>
                                        <input type="text" class="form-control form-control-lg" id="name" 
                                               name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email Address</label>
                                        <input type="email" class="form-control form-control-lg" id="email" 
                                               value="<?php echo htmlspecialchars($user['email']); ?>" disabled>
                                        <small class="text-muted">Email cannot be changed</small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label fw-bold">Phone Number</label>
                                        <input type="tel" class="form-control form-control-lg" id="phone" 
                                               name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="city" class="form-label fw-bold">City</label>
                                        <input type="text" class="form-control form-control-lg" id="city" 
                                               name="city" value="<?php echo htmlspecialchars($user['city'] ?? ''); ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control form-control-lg" id="address" 
                                          name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="zip_code" class="form-label fw-bold">Zip Code</label>
                                <input type="text" class="form-control form-control-lg" id="zip_code" 
                                       name="zip_code" value="<?php echo htmlspecialchars($user['zip_code'] ?? ''); ?>">
                            </div>

                            <hr>

                            <div class="d-flex gap-2">
                                <button type="submit" name="update_profile" class="btn btn-warning btn-lg fw-bold">
                                    <i class="fas fa-save"></i> Save Changes
                                </button>
                                <a href="index.php" class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Recent Orders -->
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="fas fa-box"></i> Recent Orders
                    </div>
                    <div class="card-body">
                        <?php
                        $orders = get_user_orders($user_id);
                        if (count($orders) > 0):
                        ?>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 0;
                                    foreach ($orders as $order):
                                        if ($count >= 5) break;
                                        $count++;
                                        $status_badge = match($order['status']) {
                                            'pending' => 'warning',
                                            'confirmed' => 'info',
                                            'preparing' => 'primary',
                                            'ready' => 'success',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td><?php echo format_currency($order['total_amount']); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $status_badge; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <a href="orders.php" class="btn btn-outline-primary btn-sm">
                            View All Orders <i class="fas fa-arrow-right"></i>
                        </a>
                        <?php else: ?>
                        <p class="text-muted text-center py-4">
                            <i class="fas fa-box"></i> No orders yet. <a href="menu.php">Start shopping</a>
                        </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="card border-danger mt-4">
                    <div class="card-header bg-danger text-white fw-bold">
                        <i class="fas fa-exclamation-triangle"></i> Danger Zone
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Once you logout, you will need to login again to access your account.</p>
                        <a href="logout.php" class="btn btn-danger" onclick="return confirm('Are you sure?');">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
