<?php
require_once 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_message('Please login to view your cart', 'error');
    redirect('login.php');
}

// Handle remove from cart
if (isset($_GET['remove'])) {
    $cart_id = (int)$_GET['remove'];
    remove_from_cart($cart_id);
    set_message('Item removed from cart', 'success');
    redirect('cart.php');
}

// Handle update quantity
if (isset($_POST['update_quantity'])) {
    $cart_id = (int)$_POST['cart_id'];
    $quantity = (int)$_POST['quantity'];
    
    if ($quantity > 0) {
        global $conn;
        $conn->query("UPDATE cart SET quantity = $quantity WHERE id = $cart_id AND user_id = " . get_user_id());
    }
    redirect('cart.php');
}

$cart_items = get_cart_items();
$total = get_cart_total();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - <?php echo SITE_NAME; ?></title>
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
                <i class="fas fa-shopping-cart"></i> Shopping Cart
            </h1>
        </div>
    </section>

    <!-- Cart Content -->
    <div class="container my-5">
        <?php if (count($cart_items) > 0): ?>
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">
                            <i class="fas fa-list"></i> Cart Items (<?php echo count($cart_items); ?>)
                        </h5>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?php echo IMAGES_PATH . 'products/' . ($item['image'] ?? 'default.jpg'); ?>" 
                                                     alt="<?php echo $item['name']; ?>"
                                                     style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px;"
                                                     onerror="this.src='https://via.placeholder.com/60x60?text=<?php echo urlencode($item['name']); ?>'">
                                                <strong><?php echo htmlspecialchars($item['name']); ?></strong>
                                            </div>
                                        </td>
                                        <td><?php echo format_currency($item['price']); ?></td>
                                        <td>
                                            <form method="POST" style="display: inline-block;">
                                                <input type="hidden" name="cart_id" value="<?php echo $item['id']; ?>">
                                                <input type="hidden" name="update_quantity" value="1">
                                                <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                                       min="1" max="100" class="form-control" style="width: 80px;"
                                                       onchange="this.form.submit();">
                                            </form>
                                        </td>
                                        <td class="text-warning fw-bold">
                                            <?php echo format_currency($item['price'] * $item['quantity']); ?>
                                        </td>
                                        <td>
                                            <a href="?remove=<?php echo $item['id']; ?>" 
                                               class="btn btn-sm btn-danger"
                                               onclick="return confirm('Remove this item?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-end border-top pt-3">
                            <a href="menu.php" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Continue Shopping
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Order Summary</h5>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span><?php echo format_currency($total); ?></span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span class="text-success">Free</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (10%):</span>
                                <span><?php echo format_currency($total * 0.10); ?></span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fs-5 fw-bold">
                                <span>Total:</span>
                                <span class="text-warning">
                                    <?php echo format_currency($total * 1.10); ?>
                                </span>
                            </div>
                        </div>

                        <a href="checkout.php" class="btn btn-warning w-100 fw-bold btn-lg">
                            <i class="fas fa-credit-card"></i> Proceed to Checkout
                        </a>

                        <button type="button" class="btn btn-outline-danger w-100 mt-2" 
                                onclick="if(confirm('Clear entire cart?')) window.location='clear-cart.php';">
                            <i class="fas fa-times"></i> Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php else: ?>
        <!-- Empty Cart -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0">
                    <div class="card-body py-5">
                        <i class="fas fa-shopping-cart" style="font-size: 4rem; color: #ccc;"></i>
                        <h3 class="mt-4 fw-bold">Your Cart is Empty</h3>
                        <p class="text-muted">Start adding delicious coffee to your cart!</p>
                        <a href="menu.php" class="btn btn-warning btn-lg fw-bold">
                            <i class="fas fa-shopping-bag"></i> Shop Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
