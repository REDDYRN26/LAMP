<?php
require_once 'config.php';

$selected_category = isset($_GET['category']) ? sanitize($_GET['category']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - <?php echo SITE_NAME; ?></title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
</head>
<body>
    <?php require_once 'header.php'; ?>

    <!-- Page Header -->
    <section class="bg-dark text-white py-5">
        <div class="container">
            <h1 class="fw-bold mb-2">
                <i class="fas fa-coffee"></i> Our Menu
            </h1>
            <p class="lead text-muted">Explore our premium collection of coffee</p>
        </div>
    </section>

    <!-- Menu Content -->
    <div class="container my-5">
        <div class="row">
            <!-- Sidebar - Categories -->
            <div class="col-md-3 mb-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-3">Categories</h5>
                        <div class="list-group list-group-flush">
                            <a href="menu.php" class="list-group-item list-group-item-action <?php echo !$selected_category ? 'active' : ''; ?>">
                                <i class="fas fa-list-ul"></i> All Items
                            </a>
                            <?php
                            $categories = get_categories();
                            foreach ($categories as $category):
                                $is_active = $selected_category === $category ? 'active' : '';
                            ?>
                            <a href="?category=<?php echo urlencode($category); ?>" 
                               class="list-group-item list-group-item-action <?php echo $is_active; ?>">
                                <i class="fas fa-tag"></i> <?php echo htmlspecialchars($category); ?>
                            </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-md-9">
                <div class="row" id="products-grid">
                    <?php
                    $products = get_products($selected_category);
                    
                    if ($products->num_rows > 0):
                        while ($product = $products->fetch_assoc()):
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm border-0 product-card">
                            <!-- Product Image -->
                            <div class="product-image-container bg-light" style="height: 300px; overflow: hidden;">
                                <img src="<?php echo IMAGES_PATH . 'products/' . ($product['image'] ?? 'default.jpg'); ?>" 
                                     alt="<?php echo $product['name']; ?>"
                                     class="card-img-top h-100 object-fit-cover"
                                     onerror="this.src='https://via.placeholder.com/300x300?text=<?php echo urlencode($product['name']); ?>'">
                            </div>
                            
                            <div class="card-body d-flex flex-column">
                                <div class="flex-grow-1">
                                    <h5 class="card-title fw-bold">
                                        <?php echo htmlspecialchars($product['name']); ?>
                                    </h5>
                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars($product['description']); ?>
                                    </p>
                                    <p class="text-muted small mb-2">
                                        <span class="badge bg-secondary">
                                            <?php echo $product['category']; ?>
                                        </span>
                                    </p>
                                    <p class="text-muted small">
                                        <i class="fas fa-cubes"></i> Stock: 
                                        <?php 
                                            if ($product['stock'] > 10) {
                                                echo '<span class="text-success">In Stock (' . $product['stock'] . ')</span>';
                                            } elseif ($product['stock'] > 0) {
                                                echo '<span class="text-warning">Low Stock (' . $product['stock'] . ')</span>';
                                            } else {
                                                echo '<span class="text-danger">Out of Stock</span>';
                                            }
                                        ?>
                                    </p>
                                </div>

                                <!-- Card Footer -->
                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="text-warning fw-bold mb-0">
                                            <?php echo format_currency($product['price']); ?>
                                        </h6>
                                        <?php if ($product['stock'] > 0): ?>
                                            <?php if (is_logged_in()): ?>
                                                <button class="btn btn-sm btn-warning" onclick="addToCart(<?php echo $product['id']; ?>)">
                                                    <i class="fas fa-cart-plus"></i>
                                                </button>
                                            <?php else: ?>
                                                <a href="login.php" class="btn btn-sm btn-warning">
                                                    <i class="fas fa-cart-plus"></i>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled>
                                                Out of Stock
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                        endwhile;
                    else:
                    ?>
                    <div class="col-12">
                        <div class="alert alert-info text-center" role="alert">
                            <i class="fas fa-info-circle"></i> No products available in this category.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php require_once 'footer.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
