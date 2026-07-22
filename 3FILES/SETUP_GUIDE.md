# 🚀 CoffeeLand - Complete Setup Guide

## ⚡ 5-Minute Quick Setup

### Prerequisites
- PHP 7.4+ with MySQL support
- MySQL Server (5.7+ or MariaDB)
- A web server (Apache, Nginx, or built-in PHP server)
- Basic knowledge of command line

### Step-by-Step Setup

#### **Step 1: Download & Extract (2 minutes)**

```bash
# Download files to your web directory
# For XAMPP/WAMP:
cp -r coffeeshop /path/to/htdocs/

# Navigate to directory
cd /path/to/htdocs/coffeeshop
```

#### **Step 2: Create Database (1 minute)**

**Option A: Using phpMyAdmin**
1. Open `http://localhost/phpmyadmin`
2. Click "New"
3. Enter database name: `coffeeshop`
4. Click "Create"
5. Click "Import"
6. Choose `coffeeshop_database.sql`
7. Click "Import"

**Option B: Using Command Line**
```bash
# Open MySQL
mysql -u root -p

# In MySQL shell:
CREATE DATABASE coffeeshop;
USE coffeeshop;
SOURCE coffeeshop_database.sql;
EXIT;
```

#### **Step 3: Configure Database (1 minute)**

Edit `config.php`:
```php
// Line 4-7
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Your MySQL username
define('DB_PASS', 'your_password');  // Your MySQL password
define('DB_NAME', 'coffeeshop');
```

#### **Step 4: Create Uploads Directory (30 seconds)**

```bash
# Create directories
mkdir -p uploads/products
chmod -R 755 uploads

# Check if created
ls -la uploads/
```

#### **Step 5: Start & Access (30 seconds)**

**Using XAMPP/WAMP:**
1. Start Apache & MySQL services
2. Open browser: `http://localhost/coffeeshop/`

**Using Built-in PHP Server:**
```bash
cd /path/to/coffeeshop
php -S localhost:8000

# Open browser: http://localhost:8000
```

✅ **Done!** You're ready to go.

---

## 📋 Complete File Checklist

```
coffeeshop/
├── ✅ index.php                    (Homepage)
├── ✅ menu.php                     (Product listing)
├── ✅ cart.php                     (Shopping cart)
├── ✅ checkout.php                 (Order placement)
├── ✅ orders.php                   (Order history)
├── ✅ profile.php                  (User profile)
├── ✅ login.php                    (Login page)
├── ✅ register.php                 (Registration page)
├── ✅ add-to-cart.php              (AJAX handler)
├── ✅ clear-cart.php               (Clear cart)
├── ✅ logout.php                   (Logout)
├── ✅ header.php                   (Navigation)
├── ✅ footer.php                   (Footer)
├── ✅ config.php                   (Database & Config)
├── ✅ style.css                    (Styling)
├── ✅ script.js                    (JavaScript)
├── ✅ coffeeshop_database.sql      (Database)
├── ✅ README.md                    (Full documentation)
├── ✅ SETUP_GUIDE.md               (This file)
└── 📁 uploads/
    └── 📁 products/                (Product images)
```

---

## 🔐 Test the System

### Create Test Account

1. Go to `http://localhost/coffeeshop/register.php`
2. Fill in:
   - Name: `John Coffee`
   - Email: `john@example.com`
   - Password: `password123`
   - Phone: `+1-555-0100`
   - Address: `123 Coffee Street`
3. Click "Create Account"
4. Login with email and password

### Test Shopping Flow

1. **Browse Products**: Click "Menu" to see all products
2. **Filter by Category**: Click category sidebar items
3. **Add to Cart**: Click cart button (must be logged in)
4. **View Cart**: Click cart icon in navbar
5. **Checkout**: Click "Proceed to Checkout"
6. **Place Order**: Fill address and click "Place Order"
7. **View Orders**: Click "My Orders" to see history

---

## 🔧 Configuration Options

### Site Settings

**config.php (Lines 5-9):**
```php
define('SITE_NAME', 'CoffeeLand');              // Your shop name
define('SITE_URL', 'http://localhost/coffeeshop/');  // Your URL
define('ADMIN_EMAIL', 'admin@coffeeland.com');  // Contact email
define('SESSION_TIMEOUT', 3600);                // Session timeout (seconds)
```

### Database Settings

**config.php (Lines 1-3):**
```php
define('DB_HOST', 'localhost');     // MySQL host
define('DB_USER', 'root');          // Username
define('DB_PASS', 'password');      // Password
define('DB_NAME', 'coffeeshop');    // Database name
```

### Upload Settings

**config.php (Lines 11-13):**
```php
define('UPLOADS_PATH', BASE_PATH . 'uploads/');
define('IMAGES_PATH', 'uploads/');
```

---

## 🖼️ Adding Product Images

### How to Add Images

1. **Save image** to `uploads/products/` folder
   - Supported: JPG, PNG, GIF, WebP
   - Recommended size: 300x300px or larger
   - Naming: `product-name.jpg`

2. **Update database:**
   ```sql
   UPDATE products SET image = 'espresso.jpg' WHERE id = 1;
   ```

3. **Images will auto-display** in product cards

**Note:** If image doesn't exist, system auto-generates placeholder.

---

## 🗃️ Database Structure

### Users Table
```sql
id | name | email | phone | password | address | city | zip_code
```

### Products Table
```sql
id | name | description | category | price | image | stock | is_available
```

### Orders Table
```sql
id | user_id | total_amount | status | payment_method | delivery_address | special_instructions
```

### Order Items Table
```sql
id | order_id | product_id | quantity | price
```

### Cart Table
```sql
id | user_id | product_id | quantity
```

---

## 🐛 Troubleshooting

### Problem: "Cannot connect to database"

**Solution:**
1. Check MySQL is running
   ```bash
   # Windows
   mysql --version
   
   # Linux/Mac
   which mysql
   ```

2. Verify credentials in `config.php`
3. Test connection:
   ```bash
   mysql -u root -p < coffeeshop_database.sql
   ```

### Problem: "Table doesn't exist"

**Solution:**
1. Import database again:
   ```bash
   mysql -u root -p coffeeshop < coffeeshop_database.sql
   ```

2. Verify database exists:
   ```bash
   mysql -u root -p -e "SHOW DATABASES;"
   ```

### Problem: "Permission denied" on uploads

**Solution:**
```bash
# Fix permissions
chmod -R 755 uploads/
chmod -R 755 uploads/products/

# Linux/Mac - set owner
sudo chown -R www-data:www-data uploads/
```

### Problem: "Images not showing"

**Solution:**
1. Check directory exists: `uploads/products/`
2. Check image filenames in database
3. Check file permissions: `chmod 644 uploads/products/*.jpg`
4. Verify `IMAGES_PATH` in config.php

### Problem: "Session expired"

**Solution:**
Edit `config.php`:
```php
// Increase timeout from 3600 to 7200 (2 hours)
define('SESSION_TIMEOUT', 7200);
```

### Problem: "Cannot add to cart"

**Solution:**
1. Must be logged in
2. Check if `sessions` directory is writable
3. Clear browser cookies and login again

---

## 🎯 Quick Customization

### Change Logo/Branding

In `header.php` (Line 26):
```php
<a class="navbar-brand" href="index.php">
    <i class="fas fa-coffee"></i><?php echo SITE_NAME; ?>
</a>
```

### Change Colors

In `style.css` (Lines 7-8):
```css
--secondary-color: #ffc107;  /* Change to your color */
```

### Modify Homepage Text

In `index.php` (Line 23):
```php
<h1 class="display-3 fw-bold mb-3">
    <i class="fas fa-coffee text-warning"></i>
    Your Custom Welcome Text
</h1>
```

### Add Menu Categories

In `config.php`, add to SQL:
```sql
INSERT INTO products (name, description, category, price, image) VALUES
('Your Coffee', 'Your Description', 'New Category', 3.99, 'image.jpg');
```

---

## 📞 Server Requirements

### Minimum Requirements
- PHP 7.4+
- MySQL 5.7+ or MariaDB 10.2+
- 50MB disk space
- Apache/Nginx with mod_rewrite

### Recommended
- PHP 8.0+
- MySQL 8.0+
- 100MB disk space
- SSD storage
- 1GB RAM

---

## 🔐 Security Checklist

- [ ] Change database password in `config.php`
- [ ] Change `ADMIN_EMAIL` to your email
- [ ] Set proper file permissions (755 for dirs, 644 for files)
- [ ] Keep `config.php` private
- [ ] Update `SITE_URL` to your domain
- [ ] Enable HTTPS when live
- [ ] Regular database backups
- [ ] Keep PHP updated

---

## 📱 Testing on Different Devices

### Desktop
```
http://localhost/coffeeshop/
```

### Mobile (Local Network)
```
http://YOUR_IP_ADDRESS:8000/coffeeshop/
```

### Using Built-in Server
```bash
php -S 0.0.0.0:8000
```

---

## 🚀 Going Live

### Before Deployment

1. **Update config.php:**
   ```php
   define('SITE_URL', 'https://yourdomain.com/');
   define('DB_HOST', 'your-db-host');
   define('DB_USER', 'your-db-user');
   define('DB_PASS', 'strong-password');
   ```

2. **Enable HTTPS** on your domain

3. **Set proper permissions:**
   ```bash
   chmod -R 755 uploads/
   chmod 644 config.php
   ```

4. **Create .htaccess** for security:
   ```apache
   # Protect config
   <Files config.php>
       Order Allow,Deny
       Deny from all
   </Files>
   ```

5. **Backup database:**
   ```bash
   mysqldump -u root -p coffeeshop > backup.sql
   ```

---

## ✅ Final Checklist

- [ ] Files extracted to correct directory
- [ ] Database created and imported
- [ ] `config.php` updated with credentials
- [ ] `uploads/products/` directory created
- [ ] Website accessible in browser
- [ ] Test account created
- [ ] Product images added
- [ ] Test order placed
- [ ] All links working
- [ ] Mobile responsive
- [ ] Ready for production

---

## 📞 Support Resources

1. **Check README.md** for detailed documentation
2. **Review config.php** comments for settings
3. **Check browser console** (F12) for JavaScript errors
4. **Check server error logs** for PHP errors
5. **Verify database** with phpMyAdmin

---

**Congratulations! 🎉**

Your CoffeeLand coffee shop website is now live and ready for orders!

Need help? Refer to README.md for comprehensive documentation.

---

*Last Updated: 2024*
*Version: 1.0*
