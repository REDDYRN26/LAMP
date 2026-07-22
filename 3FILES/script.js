// ======================================
// Coffee Shop - JavaScript
// ======================================

// ======================================
// Add to Cart Function
// ======================================

function addToCart(productId, quantity = 1) {
    const data = new FormData();
    data.append('product_id', productId);
    data.append('quantity', quantity);

    fetch('add-to-cart.php', {
        method: 'POST',
        body: data
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Item added to cart!', 'success');
            updateCartCount(data.cart_count);
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding to cart', 'error');
    });
}

// ======================================
// Update Cart Count Badge
// ======================================

function updateCartCount(count) {
    const badge = document.querySelector('.nav-link .badge');
    if (badge) {
        badge.textContent = count;
    }
}

// ======================================
// Show Notifications
// ======================================

function showNotification(message, type = 'info') {
    const alertClass = type === 'error' ? 'alert-danger' : 
                      type === 'success' ? 'alert-success' : 
                      'alert-info';

    const alertHTML = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i> 
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    const container = document.querySelector('.container');
    if (container) {
        const messageDiv = document.createElement('div');
        messageDiv.innerHTML = alertHTML;
        container.insertBefore(messageDiv.firstElementChild, container.firstChild);

        // Auto dismiss after 5 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }
}

// ======================================
// Format Currency
// ======================================

function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
}

// ======================================
// Form Validation
// ======================================

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function validatePhoneNumber(phone) {
    const phoneRegex = /^[\d\s\-\+\(\)]{10,}$/;
    return phoneRegex.test(phone);
}

// ======================================
// Cart Item Quantity Control
// ======================================

document.addEventListener('DOMContentLoaded', function() {
    // Quantity input controls
    const quantityInputs = document.querySelectorAll('input[type="number"][name="quantity"]');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            if (this.value < 1) {
                this.value = 1;
            }
            if (this.value > 100) {
                this.value = 100;
            }
        });
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// ======================================
// Confirm Delete
// ======================================

function confirmDelete(message = 'Are you sure?') {
    return confirm(message);
}

// ======================================
// Search Products
// ======================================

function searchProducts(query) {
    if (query.length === 0) {
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = 'block';
        });
        return;
    }

    const lowerQuery = query.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        const name = card.querySelector('.card-title')?.textContent.toLowerCase() || '';
        const category = card.querySelector('.badge')?.textContent.toLowerCase() || '';
        
        if (name.includes(lowerQuery) || category.includes(lowerQuery)) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// ======================================
// Filter Products by Category
// ======================================

function filterByCategory(category) {
    if (!category) {
        document.querySelectorAll('.product-card').forEach(card => {
            card.style.display = 'block';
        });
        return;
    }

    document.querySelectorAll('.product-card').forEach(card => {
        const cardCategory = card.querySelector('.badge')?.textContent.trim();
        if (cardCategory === category) {
            card.style.display = 'block';
        } else {
            card.style.display = 'none';
        }
    });
}

// ======================================
// Smooth Scroll to Section
// ======================================

function scrollToSection(sectionId) {
    const element = document.getElementById(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}

// ======================================
// Print Order Receipt
// ======================================

function printOrderReceipt(orderId) {
    const printWindow = window.open(``, `print-${orderId}`, 'height=600,width=800');
    printWindow.document.write(`
        <html>
            <head>
                <title>Order #${orderId}</title>
                <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
                <style>
                    body { padding: 20px; font-family: Arial, sans-serif; }
                    .receipt { max-width: 600px; margin: 0 auto; border: 1px solid #ddd; padding: 20px; }
                    .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .items { margin: 20px 0; }
                    .item { display: flex; justify-content: space-between; margin: 10px 0; }
                    .total { border-top: 2px solid #333; padding-top: 10px; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="receipt">
                    <div class="header">
                        <h2>CoffeeLand</h2>
                        <p>Order Receipt</p>
                        <p>Order #${orderId}</p>
                    </div>
                    <div class="items" id="items"></div>
                    <div class="total">
                        <div style="text-align: right;">Total: <span id="total">$0.00</span></div>
                    </div>
                </div>
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}

// ======================================
// Download Invoice
// ======================================

function downloadInvoice(orderId) {
    fetch(`get-invoice.php?order_id=${orderId}`)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `invoice-${orderId}.pdf`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error downloading invoice', 'error');
        });
}

// ======================================
// LocalStorage Cart (Fallback)
// ======================================

const CartStorage = {
    key: 'coffeeshop_cart',
    
    set: function(cart) {
        localStorage.setItem(this.key, JSON.stringify(cart));
    },
    
    get: function() {
        const cart = localStorage.getItem(this.key);
        return cart ? JSON.parse(cart) : [];
    },
    
    clear: function() {
        localStorage.removeItem(this.key);
    }
};

// ======================================
// Password Strength Indicator
// ======================================

function checkPasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    const strengthTexts = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const strengthColors = ['danger', 'danger', 'warning', 'info', 'success', 'success'];
    
    return {
        strength: strength,
        text: strengthTexts[strength],
        color: strengthColors[strength]
    };
}

// ======================================
// Dark Mode Toggle (Optional)
// ======================================

const DarkMode = {
    key: 'coffeeshop_darkmode',
    
    enable: function() {
        document.body.classList.add('dark-mode');
        localStorage.setItem(this.key, 'true');
    },
    
    disable: function() {
        document.body.classList.remove('dark-mode');
        localStorage.setItem(this.key, 'false');
    },
    
    toggle: function() {
        if (this.isEnabled()) {
            this.disable();
        } else {
            this.enable();
        }
    },
    
    isEnabled: function() {
        return localStorage.getItem(this.key) === 'true';
    }
};

// ======================================
// Initialize on Page Load
// ======================================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap components
    if (typeof bootstrap !== 'undefined') {
        // Bootstrap is loaded
    }
    
    // Log version
    console.log('CoffeeLand - Coffee Shop System v1.0');
});

// ======================================
// Error Handling
// ======================================

window.addEventListener('error', function(event) {
    console.error('Error:', event.error);
});

// ======================================
// Export for external use
// ======================================

window.CoffeeShop = {
    addToCart,
    showNotification,
    validateEmail,
    validatePhoneNumber,
    formatCurrency,
    searchProducts,
    filterByCategory,
    scrollToSection,
    printOrderReceipt,
    downloadInvoice,
    CartStorage,
    DarkMode,
    checkPasswordStrength
};
