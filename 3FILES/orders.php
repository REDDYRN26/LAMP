<?php
require_once 'config.php';

// Check if user is logged in
if (!is_logged_in()) {
    set_message('Please login to view your orders', 'error');
    redirect('login.php');
}

$user_id = get_user_id();
$orders = get_user_orders($user_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - <?php echo SITE_NAME; ?></title>
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
                <i class="fas fa-box"></i> My Orders
            </h1>
        </div>
    </section>

    <!-- Orders Content -->
    <div class="container my-5">
        <?php if (count($orders) > 0): ?>
        <div class="row">
            <?php foreach ($orders as $order): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <!-- Order Header -->
                    <div class="card-header bg-dark text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold mb-1">Order #<?php echo $order['id']; ?></h6>
                                <small class="text-muted">
                                    <?php echo date('M d, Y', strtotime($order['created_at'])); ?>
                                </small>
                            </div>
                            <span class="badge bg-warning text-dark">
                                <?php 
                                    $status = ucfirst($order['status']);
                                    $badge_class = match($order['status']) {
                                        'pending' => 'bg-warning',
                                        'confirmed' => 'bg-info',
                                        'preparing' => 'bg-primary',
                                        'ready' => 'bg-success',
                                        'completed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        default => 'bg-secondary'
                                    };
                                ?>
                                <span class="badge <?php echo $badge_class; ?>">
                                    <?php echo $status; ?>
                                </span>
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Items:</h6>
                        <div class="list-group list-group-flush mb-3">
                            <?php
                            $order_items = get_order_items($order['id']);
                            foreach ($order_items as $item):
                            ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between">
                                    <span>
                                        <?php echo htmlspecialchars($item['name']); ?>
                                        <br>
                                        <small class="text-muted">
                                            Qty: <?php echo $item['quantity']; ?> × 
                                            <?php echo format_currency($item['price']); ?>
                                        </small>
                                    </span>
                                    <strong><?php echo format_currency($item['price'] * $item['quantity']); ?></strong>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Order Total -->
                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between">
                                <strong>Total:</strong>
                                <h6 class="text-warning fw-bold mb-0">
                                    <?php echo format_currency($order['total_amount']); ?>
                                </h6>
                            </div>
                        </div>

                        <!-- Order Details -->
                        <div class="mt-3 pt-3 border-top">
                            <p class="small mb-2">
                                <i class="fas fa-map-marker-alt text-warning"></i>
                                <strong>Delivery Address:</strong><br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($order['delivery_address']); ?>
                                </small>
                            </p>
                            <p class="small mb-2">
                                <i class="fas fa-credit-card text-warning"></i>
                                <strong>Payment Method:</strong><br>
                                <small class="text-muted">
                                    <?php echo ucfirst(str_replace('_', ' ', $order['payment_method'])); ?>
                                </small>
                            </p>
                            <?php if ($order['special_instructions']): ?>
                            <p class="small mb-0">
                                <i class="fas fa-sticky-note text-warning"></i>
                                <strong>Instructions:</strong><br>
                                <small class="text-muted">
                                    <?php echo htmlspecialchars($order['special_instructions']); ?>
                                </small>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    <div class="card-footer bg-light">
                        <button class="btn btn-sm btn-outline-primary w-100" 
                                onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                            <i class="fas fa-eye"></i> View Details
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Order Timeline for Latest Order -->
        <?php if (!empty($orders)): ?>
        <div class="row mt-5">
            <div class="col-md-8 mx-auto">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning text-dark fw-bold">
                        <i class="fas fa-hourglass"></i> Order Status Timeline
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Order Confirmed</h6>
                                    <p class="text-muted small">Your order has been confirmed</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-warning"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Preparing</h6>
                                    <p class="text-muted small">We're preparing your delicious coffee</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Ready for Delivery</h6>
                                    <p class="text-muted small">Your order is ready and will be delivered soon</p>
                                </div>
                            </div>

                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="fw-bold">Delivered</h6>
                                    <p class="text-muted small">Your order has been delivered</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        <!-- No Orders -->
        <div class="row justify-content-center">
            <div class="col-md-6 text-center">
                <div class="card border-0">
                    <div class="card-body py-5">
                        <i class="fas fa-box" style="font-size: 4rem; color: #ccc;"></i>
                        <h3 class="mt-4 fw-bold">No Orders Yet</h3>
                        <p class="text-muted">Start placing orders to see them here!</p>
                        <a href="menu.php" class="btn btn-warning btn-lg fw-bold">
                            <i class="fas fa-shopping-bag"></i> Order Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php require_once 'footer.php'; ?>

    <!-- Timeline CSS -->
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 30px;
            position: relative;
        }

        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 50px;
            height: 40px;
            width: 2px;
            background-color: #e9ecef;
        }

        .timeline-marker {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            margin-right: 20px;
            flex-shrink: 0;
        }

        .timeline-content h6 {
            margin-bottom: 5px;
        }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
        function viewOrderDetails(orderId) {
            alert('Order details for #' + orderId);
        }
    </script>
</body>
</html>
