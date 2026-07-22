# 🎯 CoffeeLand - Complete PHP E-Commerce Coffee Shop

A fully functional, professional PHP e-commerce website for a coffee shop with user authentication, shopping cart, order management, and beautiful UI.

## ✨ Features

✅ **User Management**
- User registration and login
- Secure password hashing (bcrypt)
- Profile management
- Session management

✅ **Shopping Features**
- Browse coffee products by category
- Add to cart functionality
- Cart management (add, remove, update quantity)
- Real-time cart count
- Shopping cart persistence

✅ **Ordering System**
- Checkout page with order summary
- Multiple payment methods
- Delivery address management
- Special instructions for orders
- Order confirmation and tracking

✅ **Order Management**
- View order history
- Track order status (Pending, Confirming, Preparing, Ready, Completed)
- Order details with items list
- Order timeline visualization

✅ **Frontend Features**
- Responsive design (Mobile, Tablet, Desktop)
- Beautiful product cards with images
- Product filtering by category
- Testimonials section
- Professional navigation bar
- Toast notifications
- Form validation

✅ **Database**
- MySQL database with proper relations
- Product catalog with categories
- Order tracking system
- User profiles
- Cart management

## 📁 Project Structure

```
coffeeshop/
├── index.php                 # Homepage
├── menu.php                  # Product listing page
├── cart.php                  # Shopping cart page
├── checkout.php              # Checkout page
├── orders.php                # Order history page
├── login.php                 # Login page
├── register.php              # Registration page
├── add-to-cart.php          # AJAX add to cart handler
├── clear-cart.php           # Clear cart functionality
├── logout.php               # Logout functionality
├── header.php               # Header include file
├── footer.php               # Footer include file
├── config.php               # Configuration & Database functions
├── style.css                # CSS styling
├── script.js                # JavaScript functionality
├── coffeeshop_database.sql  # Database setup script
├── uploads/                 # Upload directory for images
│   └── products/           # Product images directory
└── README.md               # This file
```

## 🚀 Installation & Setup

### Step 1: Download Files
Copy all files to your web server's root directory or a subdirectory:
```
htdocs/coffeeshop/ (for XAMPP/WAMP)
or
www/coffeeshop/ (for other servers)
```

### Step 2: Database Setup

1. Open **phpMyAdmin** or MySQL command line
2. Create a new database named `coffeeshop`:
   ```sql
   CREATE DATABASE coffeeshop;
   ```
3. Import the database file:
   - Go to phpMyAdmin → Import
   - Select `coffeeshop_database.sql`
   - Click Import

OR run the SQL manually:
```sql
mysql -u root -p < coffeeshop_database.sql
```

### Step 3: Update Configuration

Edit `config.php` and update database credentials:

```php
define('DB_HOST', 'localhost');     // MySQL host
define('DB_USER', 'root');          // MySQL username
define('DB_PASS', 'password');      // MySQL password (change this!)
define('DB_NAME', 'coffeeshop');    // Database name
```

### Step 4: Create Upload Directory

Create a `uploads/products/` directory if it doesn't exist:
```bash
mkdir -p uploads/products
chmod 755 uploads/products
```

### Step 5: Access the Website

Open your browser and go to:
```
http://localhost/coffeeshop/
```

## 🔐 Test Credentials

After running the SQL file, you can use these credentials:

**Admin/Demo Account:**
- Email: `demo@example.com`
- Password: `password123`

## 📄 File Descriptions

### Core Files

**config.php**
- Database connection
- Helper functions
- User authentication
- Product management functions
- Cart operations
- Order creation and management

**index.php**
- Homepage with hero section
- Featured products
- Testimonials
- Call-to-action sections

**menu.php**
- Product listing with filtering
- Category sidebar
- Product cards with images
- Add to cart functionality

**cart.php**
- Shopping cart display
- Item quantity management
- Remove items from cart
- Order summary
- Proceed to checkout

**checkout.php**
- Order placement form
- Delivery address input
- Payment method selection
- Special instructions
- Order review before confirmation

**orders.php**
- Order history
- Order status tracking
- Order details (items, total, address)
- Order timeline visualization

**login.php** & **register.php**
- User authentication forms
- Form validation
- Password hashing
- Session management

### Include Files

**header.php**
- Navigation bar
- Cart badge
- User menu
- Responsive navbar

**footer.php**
- Footer with links
- Contact information
- Social media links
- Copyright

### Styling & JavaScript

**style.css**
- Professional styling
- Responsive design
- Bootstrap 5 integration
- Custom animations
- Print styles

**script.js**
- Add to cart functionality
- Form validation
- Notifications system
- Search and filter
- Utility functions

### Database

**coffeeshop_database.sql**
- Users table (registration, profiles)
- Products table (menu items)
- Orders table (order tracking)
- Order items table (individual items)
- Cart table (temporary cart)

## 🎨 Customization

### Change Site Name
In `config.php`:
```php
define('SITE_NAME', 'Your Coffee Shop Name');
```

### Change Colors
In `style.css`:
```css
:root {
    --primary-color: #212529;
    --secondary-color: #ffc107;  /* Change this to your brand color */
    // ... other variables
}
```

### Add More Products
In `config.php`, add to the database or use:
```sql
INSERT INTO products (name, description, category, price, image) VALUES
('Product Name', 'Description', 'Category', 4.99, 'image.jpg');
```

### Modify Payment Methods
In `checkout.php`, add more payment options to the form

## 🖼️ Adding Product Images

1. Place image files in `uploads/products/` directory
2. Update the image filename in database:
   ```sql
   UPDATE products SET image = 'coffee-name.jpg' WHERE id = 1;
   ```
3. Supported formats: JPG, PNG, GIF, WebP

**Placeholder images** will auto-generate if image not found.

## 🔍 Database Schema

### Users Table
```sql
id, name, email, phone, password, address, city, zip_code, created_at
```

### Products Table
```sql
id, name, description, category, price, image, stock, is_available, created_at
```

### Orders Table
```sql
id, user_id, total_amount, status, payment_method, delivery_address, 
special_instructions, created_at, updated_at
```

### Order Items Table
```sql
id, order_id, product_id, quantity, price
```

### Cart Table
```sql
id, user_id, product_id, quantity, added_at
```

## 🔒 Security Features

✅ SQL Injection Prevention (Prepared Statements)
✅ Password Hashing (bcrypt)
✅ Session Management
✅ CSRF Protection
✅ Input Sanitization
✅ Secure Cookie Handling

## 📱 Responsive Design

- **Mobile**: 320px - 480px
- **Tablet**: 481px - 768px
- **Desktop**: 769px+

All pages are fully responsive and mobile-optimized.

## 🚀 Performance

- **Bootstrap 5 CDN**: Fast loading
- **Font Awesome Icons**: Professional icons
- **Optimized CSS**: Minified and organized
- **Lazy Loading**: Images load on demand
- **Caching**: Proper cache headers

## 🐛 Troubleshooting

### "Connection failed" Error
- Check MySQL is running
- Verify database credentials in `config.php`
- Ensure database exists

### "Table not found" Error
- Import `coffeeshop_database.sql` file
- Check database name in `config.php`

### Images not showing
- Create `uploads/products/` directory
- Add images to the directory
- Update image filenames in database

### Cart not working
- Clear browser cache
- Check session is enabled
- Verify user is logged in

### Products not displaying
- Insert sample products using provided SQL
- Check `is_available` is set to TRUE
- Verify category names match

## 📧 Support & Contact

For issues or questions:
1. Check the troubleshooting section
2. Review `config.php` and database setup
3. Check browser console for JavaScript errors
4. Verify file permissions (755 for directories)

## 📝 License

This project is provided as-is for educational and commercial use.

## 🎓 Learning Resources

### Functions to Learn
- `get_products()` - Retrieve products
- `get_cart_items()` - Get user's cart
- `add_to_cart()` - Add item to cart
- `create_order()` - Create new order
- `login_user()` - Handle login
- `register_user()` - Handle registration

### JavaScript Functions
- `addToCart(productId)` - Add product to cart
- `showNotification(message, type)` - Display notifications
- `searchProducts(query)` - Search functionality
- `filterByCategory(category)` - Filter by category

## 🎯 Future Enhancements

- [ ] Admin dashboard
- [ ] Email notifications
- [ ] PDF invoice generation
- [ ] Customer reviews & ratings
- [ ] Coupon/discount system
- [ ] Wishlist feature
- [ ] Advanced search
- [ ] Payment gateway integration (Stripe, PayPal)
- [ ] Order analytics
- [ ] Dark mode
- [ ] Multi-language support
- [ ] API endpoints

## 📞 Quick Start Checklist

- [ ] Downloaded all files
- [ ] Created `coffeeshop` database
- [ ] Imported SQL file
- [ ] Updated `config.php` with credentials
- [ ] Created `uploads/products/` directory
- [ ] Accessed website in browser
- [ ] Registered user account
- [ ] Added product to cart
- [ ] Placed test order
- [ ] Viewed order history

## 🎉 You're Ready!

Your coffee shop website is now live! Start customizing and adding more products.

---

**Made with ☕ for Coffee Lovers**

Version: 1.0
Last Updated: 2024
