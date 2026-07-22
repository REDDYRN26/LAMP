<?php
// Coffee Shop Configuration File
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password'); // Change this to your MySQL password
define('DB_NAME', 'coffeeshop');

// Site Configuration
define('SITE_NAME', 'CoffeeLand');
define('SITE_URL', 'http://localhost/coffeeshop/');
define('ADMIN_EMAIL', 'admin@coffeeland.com');

// Path Configuration
define('BASE_PATH', __DIR__ . '/');
define('UPLOADS_PATH', BASE_PATH . 'uploads/');
define('IMAGES_PATH', 'uploads/');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour in seconds

// Database Connection
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to UTF-8
    $conn->set_charset("utf8");
    
} catch (Exception $e) {
    die("Database Error: " . $e->getMessage());
}

// Start Session
session_start();

// Helper Functions
function sanitize($data) {
    global $conn;
    return $conn->real_escape_string(trim($data));
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function redirect($url) {
    header("Location: " . SITE_URL . $url);
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function get_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function get_user_email() {
    return $_SESSION['email'] ?? null;
}

function is_admin() {
    return $_SESSION['is_admin'] ?? false;
}

function logout() {
    session_destroy();
    redirect('index.php');
}

function format_currency($amount) {
    return '$' . number_format($amount, 2);
}

function get_message() {
    if (isset($_SESSION['message'])) {
        $message = $_SESSION['message'];
        unset($_SESSION['message']);
        return $message;
    }
    return null;
}

function set_message($message, $type = 'success') {
    $_SESSION['message'] = [
        'text' => $message,
        'type' => $type
    ];
}

function display_message() {
    $message = get_message();
    if ($message) {
        $class = $message['type'] === 'error' ? 'alert-danger' : 'alert-success';
        echo '<div class="alert ' . $class . ' alert-dismissible fade show" role="alert">';
        echo $message['text'];
        echo '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
        echo '</div>';
    }
}

// Database Query Helper Functions
function get_products($category = null) {
    global $conn;
    $query = "SELECT * FROM products WHERE is_available = TRUE";
    
    if ($category) {
        $category = sanitize($category);
        $query .= " AND category = '$category'";
    }
    
    $query .= " ORDER BY name ASC";
    return $conn->query($query);
}

function get_product_by_id($id) {
    global $conn;
    $id = (int)$id;
    $result = $conn->query("SELECT * FROM products WHERE id = $id AND is_available = TRUE");
    return $result->fetch_assoc();
}

function get_categories() {
    global $conn;
    $result = $conn->query("SELECT DISTINCT category FROM products WHERE is_available = TRUE ORDER BY category ASC");
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row['category'];
    }
    return $categories;
}

function add_to_cart($product_id, $quantity = 1) {
    global $conn;
    
    if (!is_logged_in()) {
        set_message('Please login first', 'error');
        return false;
    }
    
    $user_id = get_user_id();
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;
    
    // Check if product exists in cart
    $check = $conn->query("SELECT id FROM cart WHERE user_id = $user_id AND product_id = $product_id");
    
    if ($check->num_rows > 0) {
        // Update quantity
        $conn->query("UPDATE cart SET quantity = quantity + $quantity WHERE user_id = $user_id AND product_id = $product_id");
    } else {
        // Add new item
        $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)");
    }
    
    return true;
}

function get_cart_items() {
    global $conn;
    
    if (!is_logged_in()) {
        return [];
    }
    
    $user_id = get_user_id();
    $result = $conn->query("
        SELECT c.id, c.quantity, p.id as product_id, p.name, p.price, p.image
        FROM cart c
        JOIN products p ON c.product_id = p.id
        WHERE c.user_id = $user_id
        ORDER BY c.added_at DESC
    ");
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

function get_cart_total() {
    $items = get_cart_items();
    $total = 0;
    foreach ($items as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

function remove_from_cart($cart_id) {
    global $conn;
    $cart_id = (int)$cart_id;
    return $conn->query("DELETE FROM cart WHERE id = $cart_id");
}

function clear_cart() {
    global $conn;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    return $conn->query("DELETE FROM cart WHERE user_id = $user_id");
}

function create_order($payment_method, $delivery_address, $special_instructions = '') {
    global $conn;
    
    if (!is_logged_in()) {
        return false;
    }
    
    $user_id = get_user_id();
    $total = get_cart_total();
    
    if ($total <= 0) {
        return false;
    }
    
    $payment_method = sanitize($payment_method);
    $delivery_address = sanitize($delivery_address);
    $special_instructions = sanitize($special_instructions);
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Create order
        $conn->query("INSERT INTO orders (user_id, total_amount, payment_method, delivery_address, special_instructions) 
                      VALUES ($user_id, $total, '$payment_method', '$delivery_address', '$special_instructions')");
        
        $order_id = $conn->insert_id;
        
        // Get cart items
        $cart_items = get_cart_items();
        
        // Add order items
        foreach ($cart_items as $item) {
            $product_id = $item['product_id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            $conn->query("INSERT INTO order_items (order_id, product_id, quantity, price)
                          VALUES ($order_id, $product_id, $quantity, $price)");
        }
        
        // Clear cart
        clear_cart();
        
        // Commit transaction
        $conn->commit();
        
        return $order_id;
        
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

function get_user_orders($user_id) {
    global $conn;
    $user_id = (int)$user_id;
    $result = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC");
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
    return $orders;
}

function get_order_items($order_id) {
    global $conn;
    $order_id = (int)$order_id;
    $result = $conn->query("
        SELECT oi.quantity, oi.price, p.name
        FROM order_items oi
        JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = $order_id
    ");
    
    $items = [];
    while ($row = $result->fetch_assoc()) {
        $items[] = $row;
    }
    return $items;
}

// User Management Functions
function register_user($name, $email, $password, $phone = '', $address = '') {
    global $conn;
    
    $name = sanitize($name);
    $email = sanitize($email);
    $phone = sanitize($phone);
    $address = sanitize($address);
    
    // Validate
    if (empty($name) || empty($email) || empty($password)) {
        return ['success' => false, 'message' => 'All fields are required'];
    }
    
    if (!validate_email($email)) {
        return ['success' => false, 'message' => 'Invalid email'];
    }
    
    // Check if email exists
    $check = $conn->query("SELECT id FROM users WHERE email = '$email'");
    if ($check->num_rows > 0) {
        return ['success' => false, 'message' => 'Email already registered'];
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert user
    if ($conn->query("INSERT INTO users (name, email, phone, password, address) 
                      VALUES ('$name', '$email', '$phone', '$hashed_password', '$address')")) {
        return ['success' => true, 'message' => 'Registration successful. Please login.'];
    } else {
        return ['success' => false, 'message' => 'Registration failed'];
    }
}

function login_user($email, $password) {
    global $conn;
    
    $email = sanitize($email);
    
    $result = $conn->query("SELECT id, name, email, password FROM users WHERE email = '$email'");
    
    if ($result->num_rows == 0) {
        return ['success' => false, 'message' => 'User not found'];
    }
    
    $user = $result->fetch_assoc();
    
    if (!password_verify($password, $user['password'])) {
        return ['success' => false, 'message' => 'Invalid password'];
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['email'] = $user['email'];
    
    return ['success' => true, 'message' => 'Login successful'];
}

?>
