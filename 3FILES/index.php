<?php
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Best Coffee Shop</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section bg-dark text-white py-5">
        <div class="container d-flex align-items-center justify-content-center min-vh-50">
            <div class="text-center">
                <h1 class="display-3 fw-bold mb-3">
                    <i class="fas fa-coffee text-warning"></i>
                    Welcome to <?php echo SITE_NAME; ?>
                </h1>
                <p class="lead mb-4">
                    Brew the Perfect Cup of Coffee
                </p>
                <a href="menu.php" class="btn btn-warning btn-lg fw-bold">
                    <i class="fas fa-shopping-bag"></i> Start Order Now
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Why Choose Us?</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100 shadow-sm text-center p-4">
                        <i class="fas fa-leaf text-success mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">100% Natural Beans</h5>
                        <p class="card-text text-muted">
                            Premium quality coffee beans sourced from the best farms worldwide.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100 shadow-sm text-center p-4">
                        <i class="fas fa-shipping-fast text-primary mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Fast Delivery</h5>
                        <p class="card-text text-muted">
                            Quick and reliable delivery service to your doorstep.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 h-100 shadow-sm text-center p-4">
                        <i class="fas fa-smile text-warning mb-3" style="font-size: 3rem;"></i>
                        <h5 class="card-title">Customer Support</h5>
                        <p class="card-text text-muted">
                            24/7 customer support to help you anytime.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">Featured Coffees</h2>
            <div class="row">
                <?php
                $products = get_products();
                $count = 0;
                while ($product = $products->fetch_assoc() && $count < 6):
                    $count++;
                ?>
                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 shadow-sm border-0 product-card">
                        <!-- Product Image -->
                        <div class="product-image-container bg-light" style="height: 250px; overflow: hidden;">
                            <img src="<?php echo IMAGES_PATH . 'products/' . ($product['image'] ?? 'default.jpg'); ?>" 
                                 alt="<?php echo $product['name']; ?>"
                                 class="card-img-top h-100 object-fit-cover"
                                 onerror="this.src='https://via.placeholder.com/300x250?text=<?php echo urlencode($product['name']); ?>'">
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text text-muted small">
                                <?php echo htmlspecialchars(substr($product['description'], 0, 50)); ?>...
                            </p>
                            <p class="text-muted small">
                                <i class="fas fa-tag"></i> <?php echo $product['category']; ?>
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-warning fw-bold mb-0">
                                    <?php echo format_currency($product['price']); ?>
                                </h6>
                                <?php if (is_logged_in()): ?>
                                    <button class="btn btn-sm btn-warning" onclick="addToCart(<?php echo $product['id']; ?>)">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                <?php else: ?>
                                    <a href="login.php" class="btn btn-sm btn-warning">
                                        <i class="fas fa-cart-plus"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            
            <div class="text-center mt-4">
                <a href="menu.php" class="btn btn-outline-warning btn-lg">
                    View All Menu <i class="fas fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- Call to Action Section -->
    <section class="py-5 bg-warning text-dark">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Ready to Taste the Best Coffee?</h2>
            <p class="lead mb-4">
                Join thousands of satisfied customers who enjoy our premium coffee.
            </p>
            <?php if (!is_logged_in()): ?>
                <a href="register.php" class="btn btn-dark btn-lg fw-bold me-3">
                    <i class="fas fa-user-plus"></i> Create Account
                </a>
            <?php endif; ?>
            <a href="menu.php" class="btn btn-dark btn-lg fw-bold">
                <i class="fas fa-shopping-bag"></i> Order Now
            </a>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5 fw-bold">What Our Customers Say</h2>
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "The best coffee I've ever had! The quality and taste are exceptional."
                            </p>
                            <p class="text-muted small">
                                <strong>- John Doe</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "Fast delivery and amazing customer service. Highly recommend!"
                            </p>
                            <p class="text-muted small">
                                <strong>- Sarah Smith</strong>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="mb-3">
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </div>
                            <p class="card-text">
                                "Worth every penny! The variety and quality are unmatched."
                            </p>
                            <p class="text-muted small">
                                <strong>- Mike Johnson</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
