<?php
require_once 'config.php';

if (!is_logged_in()) {
    redirect('login.php');
}

clear_cart();
set_message('Cart cleared successfully', 'success');
redirect('cart.php');
?>
