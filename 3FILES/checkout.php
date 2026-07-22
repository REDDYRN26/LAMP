<?php
require_once 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_message('Please login to checkout', 'error');
    redirect('login.php');
}

$cart_items = get_cart_items();
$total = get_cart_total();

// Redirect if cart is empty
if (count($cart_items) == 0) {
    set_message('Your cart is empty', 'error');
    redirect('cart.php');
}

// Handle order submission
if (isset($_POST['place_order'])) {
    $payment_method = sanitize($_POST['payment_method'] ?? 'credit_card');
    $delivery_address = sanitize($_POST['delivery_address']);
    $special_instructions = sanitize($_POST['special_instructions'] ?? '');

    // Validate
    if (empty($delivery_address)) {
        set_message('Delivery address is required', 'error');
    } else {
        $order_id = create_order($payment_method, $delivery_address, $special_instructions);
        
        if ($order_id) {
            set_message('Order placed successfully! Order ID: #' . $order_id, 'success');
            redirect('orders.php');
        } else {
            set_message('Failed to place order. Please try again.', 'error');
        }
    }
}

// Get user's default address
global $conn;
$user_id = get_user_id();
$user_result = $conn->query("SELECT address, city FROM users WHERE id = $user_id");
$user = $user_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - <?php echo SITE_NAME; ?></title>
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
                <i class="fas fa-credit-card"></i> Checkout
            </h1>
        </div>
    </section>

    <!-- Checkout Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Checkout Form -->
            <div class="col-lg-8">
                <form method="POST">
                    <!-- Delivery Address -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark fw-bold">
                            <i class="fas fa-map-marker-alt"></i> Delivery Address
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="delivery_address" class="form-label">Full Address *</label>
                                <textarea class="form-control" id="delivery_address" name="delivery_address" 
                                          rows="3" required placeholder="123 Main Street, Apt 4B..."
                                          ><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                <small class="text-muted">Enter your complete delivery address including street, city, and zip code</small>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark fw-bold">
                            <i class="fas fa-wallet"></i> Payment Method
                        </div>
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="credit_card" value="credit_card" checked>
                                <label class="form-check-label" for="credit_card">
                                    <i class="fas fa-credit-card"></i> Credit Card
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="debit_card" value="debit_card">
                                <label class="form-check-label" for="debit_card">
                                    <i class="fas fa-credit-card"></i> Debit Card
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="paypal" value="paypal">
                                <label class="form-check-label" for="paypal">
                                    <i class="fab fa-paypal"></i> PayPal
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" 
                                       id="cash" value="cash">
                                <label class="form-check-label" for="cash">
                                    <i class="fas fa-money-bill"></i> Cash on Delivery
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Special Instructions -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark fw-bold">
                            <i class="fas fa-sticky-note"></i> Special Instructions (Optional)
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" name="special_instructions" 
                                      rows="3" placeholder="e.g., Ring the doorbell twice, Extra hot, No sugar..." 
                                      ></textarea>
                        </div>
                    </div>

                    <!-- Order Items Summary -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-warning text-dark fw-bold">
                            <i class="fas fa-list"></i> Order Review
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Qty</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cart_items as $item): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($item['name']); ?></td>
                                            <td><?php echo format_currency($item['price']); ?></td>
                                            <td><?php echo $item['quantity']; ?></td>
                                            <td><?php echo format_currency($item['price'] * $item['quantity']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Place Order Button -->
                    <div class="d-flex gap-2">
                        <button type="submit" name="place_order" class="btn btn-warning btn-lg fw-bold flex-grow-1">
                            <i class="fas fa-check-circle"></i> Place Order
                        </button>
                        <a href="cart.php" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>

            <!-- Order Summary Sidebar -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header bg-dark text-white fw-bold">
                        <i class="fas fa-receipt"></i> Order Summary
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <p class="text-muted small mb-0">Subtotal</p>
                            <h6 class="fw-bold"><?php echo format_currency($total); ?></h6>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted small mb-0">Shipping</p>
                            <h6 class="fw-bold text-success">FREE</h6>
                        </div>

                        <div class="mb-3 border-bottom pb-3">
                            <p class="text-muted small mb-0">Tax (10%)</p>
                            <h6 class="fw-bold"><?php echo format_currency($total * 0.10); ?></h6>
                        </div>

                        <div class="mb-3">
                            <p class="text-muted small mb-0">Total Amount</p>
                            <h5 class="fw-bold text-warning">
                                <?php echo format_currency($total * 1.10); ?>
                            </h5>
                        </div>

                        <!-- Order Info -->
                        <div class="alert alert-info" role="alert">
                            <small>
                                <i class="fas fa-info-circle"></i> Your order will be prepared and delivered within 30-45 minutes.
                            </small>
                        </div>

                        <!-- Items Count -->
                        <div class="text-center border-top pt-3">
                            <p class="text-muted small mb-0">Total Items</p>
                            <h6 class="fw-bold">
                                <?php 
                                    $total_items = 0;
                                    foreach ($cart_items as $item) {
                                        $total_items += $item['quantity'];
                                    }
                                    echo $total_items;
                                ?>
                            </h6>
                        </div>
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
