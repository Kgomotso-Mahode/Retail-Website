<?php
session_start();

// Initialize users if not set (simulate a database)
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        'user@example.com' => [
            'password' => 'password123',
            'name' => '',
            'address' => '',
            'phone' => ''
        ]
    ];
}

// Products
$products = [
    // Electronics (1-4)
    1 => [
        'name' => 'IPhone 16',
        'price' => 2500,
        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=400&q=80',
        'category' => 'Electronics'
    ],
    2 => [
        'name' => 'Samsung Z Flip 2025',
        'price' => 3999,
        'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=400&q=80',
        'category' => 'Electronics'
    ],
    3 => [
        'name' => 'Lenovo Laptop',
        'price' => 6999,
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=400&q=80',
        'category' => 'Electronics'
    ],
    4 => [
        'name' => 'Samsung Laptop 2025',
        'price' => 8999,
        'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=400&q=80',
        'category' => 'Electronics'
    ],

    // Furniture (5-8)
    5 => [
        'name' => 'Modern Sofa',
        'price' => 8500,
        'image' => 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=400&q=80',
        'category' => 'Furniture'
    ],
    6 => [
        'name' => 'Dining Table Set',
        'price' => 6500,
        'image' => 'https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=400&q=80',
        'category' => 'Furniture'
    ],
    7 => [
        'name' => 'Office Chair',
        'price' => 699,
        'image' => 'https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80',
        'category' => 'Furniture'
    ],
    8 => [
        'name' => 'Queen Bed Frame',
        'price' => 4750,
        'image' => 'https://images.unsplash.com/photo-1515378791036-0648a3ef77b2?auto=format&fit=crop&w=400&q=80',
        'category' => 'Furniture'
    ],

    // Clothes (9-12)
    9 => [
        'name' => 'T-Shirt',
        'price' => 200,
        'image' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
        'category' => 'Clothes'
    ],
    10 => [
        'name' => 'Jeans',
        'price' => 400,
        'image' => 'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=400&q=80',
        'category' => 'Clothes'
    ],
    11 => [
        'name' => 'Sneakers',
        'price' => 1999,
        'image' => 'https://images.unsplash.com/photo-1519864600265-abb23847ef2c?auto=format&fit=crop&w=400&q=80',
        'category' => 'Clothes'
    ],
    12 => [
        'name' => 'Cap',
        'price' => 350,
        'image' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?auto=format&fit=crop&w=400&q=80',
        'category' => 'Clothes'
    ],
];

// Handle registration
if (isset($_POST['register'])) {
    $email = trim($_POST['email']);
    if (!isset($_SESSION['users'][$email])) {
        $_SESSION['users'][$email] = [
            'password' => $_POST['password'],
            'name' => $_POST['name'],
            'address' => $_POST['address'],
            'phone' => $_POST['phone']
        ];
        echo "<script>alert('Registration successful! Please log in.');window.location='?page=login';</script>";
        exit;
    } else {
        echo "<script>alert('Email already registered!');</script>";
    }
}

// Handle login
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $pass = $_POST['password'];
    if (isset($_SESSION['users'][$email]) && $_SESSION['users'][$email]['password'] === $pass) {
        $_SESSION['user'] = $email;
        echo "<script>alert('Login successful!');window.location='?page=dashboard';</script>";
        exit;
    } else {
        echo "<script>alert('Invalid credentials!');</script>";
    }
}

// Handle logout
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ?page=login");
    exit;
}

// Handle add to cart (only if logged in)
if (isset($_POST['add_to_cart']) && isset($_SESSION['user'])) {
    $pid = $_POST['product_id'];
    $_SESSION['cart'][$pid] = ($_SESSION['cart'][$pid] ?? 0) + 1;
    echo "<script>alert('Added to cart!');window.location='?page=dashboard';</script>";
    exit;
}

// Handle remove from cart
if (isset($_POST['remove_from_cart']) && isset($_SESSION['user'])) {
    $pid = $_POST['product_id'];
    if (isset($_SESSION['cart'][$pid])) {
        unset($_SESSION['cart'][$pid]);
        echo "<script>alert('Removed from cart!');window.location='?page=dashboard';</script>";
        exit;
    }
}

// Handle payment
if (isset($_POST['pay']) && isset($_SESSION['user'])) {
    $_SESSION['cart'] = [];
    echo "<script>alert('Payment successful! Thank you for your purchase.');window.location='?page=dashboard';</script>";
    exit;
}

// Handle search
$search_query = '';
$filtered_products = $products;
if (isset($_GET['search'])) {
    $search_query = trim($_GET['search']);
    if ($search_query !== '') {
        $filtered_products = array_filter($products, function($prod) use ($search_query) {
            return stripos($prod['name'], $search_query) !== false;
        });
    }
}

// Routing
$page = $_GET['page'] ?? (isset($_SESSION['user']) ? 'dashboard' : 'login');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mahode Store</title>
    <style>
        /* ... (same styles as before, omitted for brevity) ... */
        body { font-family: 'Segoe UI', Arial, sans-serif; background: #f7f7fa; margin: 0; padding: 0; color: #222; }
        header { background: linear-gradient(90deg, #4e54c8, #8f94fb); color: #fff; padding: 30px 0; text-align: center; box-shadow: 0 2px 8px rgba(0,0,0,0.07);}
        h1 { margin: 0; font-size: 2.5rem; letter-spacing: 2px;}
        nav { margin-top: 15px;}
        nav a { color: #fff; text-decoration: none; margin: 0 18px; font-weight: 500; transition: color 0.2s;}
        nav a:hover { color: #ffe082;}
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px;}
        .products-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 32px;}
        .product-card { background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(80,80,120,0.08); overflow: hidden; transition: transform 0.18s, box-shadow 0.18s; display: flex; flex-direction: column; align-items: center; padding: 18px 16px 24px 16px;}
        .product-card:hover { transform: translateY(-8px) scale(1.03); box-shadow: 0 8px 32px rgba(80,80,120,0.16);}
        .product-image { width: 180px; height: 180px; object-fit: cover; border-radius: 12px; margin-bottom: 16px; background: #e3e6f3; box-shadow: 0 2px 8px rgba(80,80,120,0.06);}
        .product-title { font-size: 1.2rem; font-weight: 600; margin: 8px 0 4px 0; color: #4e54c8; text-align: center;}
        .product-price { font-size: 1.1rem; font-weight: bold; color: #ff7043; margin-bottom: 14px;}
        .add-to-cart-btn { background: linear-gradient(90deg, #4e54c8, #8f94fb); color: #fff; border: none; border-radius: 8px; padding: 10px 28px; font-size: 1rem; font-weight: 500; cursor: pointer; transition: background-color 0.2s, box-shadow 0.2s; box-shadow: 0 2px 8px rgba(80,80,120,0.08);}
        .add-to-cart-btn:hover { background: linear-gradient(90deg, #8f94fb, #4e54c8); box-shadow: 0 4px 16px rgba(80,80,120,0.12);}
        .cart-item { display: flex; align-items: center; gap: 16px; margin-bottom: 16px; background: #fff; border-radius: 10px; padding: 10px 16px;}
        .pay-section { margin-top: 20px; }
        .pay-section h4 { margin: 0 0 10px 0; }
        footer { background: #23234b; color: #fff; text-align: center; padding: 18px 0; margin-top: 40px; font-size: 1rem; letter-spacing: 1px;}
        @media (max-width: 700px) { .container { padding: 0 8px; } .products-grid { grid-template-columns: 1fr; gap: 20px; } .product-image { width: 100%; height: 160px; } }
    </style>
</head>
<body>
<header>
    <h1>K&M Retail Store</h1>
    <nav>
        <?php if (!isset($_SESSION['user'])): ?>
            <a href="?page=login">Login/Register</a>
        <?php else: ?>
            <a href="?page=dashboard">Dashboard</a>
            <a href="?page=payment">Payment</a>
            <form method="post" style="display:inline;">
                <button type="submit" name="logout" class="add-to-cart-btn" style="padding:4px 14px;font-size:0.95em;">Logout</button>
            </form>
        <?php endif; ?>
    </nav>
</header>
<div class="container">
<?php
// LOGIN/REGISTER PAGE
if ($page === 'login'): ?>
    <h2>Login / Register</h2>
    <!-- Registration Form -->
    <form method="post" id="registerForm" style="margin-bottom:20px;">
        <h3>Register</h3>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="text" name="address" placeholder="Address" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <button type="submit" name="register">Register</button>
    </form>
    <!-- Login Form -->
    <form method="post" style="margin-bottom:24px;">
        <h3>Login</h3>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit" name="login">Login</button>
    </form>
    <!-- Product Search -->
    <form method="get" style="margin-bottom: 24px; text-align:center;">
        <input type="hidden" name="page" value="login">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>" style="padding:8px 14px; border-radius:6px; border:1px solid #ccc; width:220px;">
        <button type="submit" class="add-to-cart-btn" style="padding:8px 18px;">Search</button>
    </form>
    <h3>Products</h3>
    <div class="products-grid">
        <?php if (empty($filtered_products)): ?>
            <p>No products found.</p>
        <?php else: ?>
            <?php foreach ($filtered_products as $id => $prod): ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                    <div class="product-title"><?php echo htmlspecialchars($prod['name']); ?></div>
                    <div class="product-price">R<?php echo $prod['price']; ?></div>
                    <div style="color:#888;font-size:0.95em;">Login to add to cart</div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
<?php
// DASHBOARD PAGE
elseif ($page === 'dashboard' && isset($_SESSION['user'])): ?>
    <h1>Welcome</h1>
    <!-- Product Search -->
    <form method="get" style="margin-bottom: 24px; text-align:center;">
        <input type="hidden" name="page" value="dashboard">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search_query); ?>" style="padding:8px 14px; border-radius:6px; border:1px solid #ccc; width:220px;">
        <button type="submit" class="add-to-cart-btn" style="padding:8px 18px;">Search</button>
    </form>
    <h3>Products</h3>
    <div class="products-grid">
        <?php foreach ($filtered_products as $id => $prod): ?>
            <div class="product-card">
                <img src="<?php echo htmlspecialchars($prod['image']); ?>" class="product-image" alt="<?php echo htmlspecialchars($prod['name']); ?>">
                <div class="product-title"><?php echo htmlspecialchars($prod['name']); ?></div>
                <div class="product-price">R<?php echo $prod['price']; ?></div>
                <form method="post" style="margin-top:10px;">
                    <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                    <button type="submit" name="add_to_cart" class="add-to-cart-btn">Add to Cart</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
    <h3>Shopping Cart</h3>
    <div class="cart">
        <?php
        $total = 0;
        if (!empty($_SESSION['cart'])):
            foreach ($_SESSION['cart'] as $pid => $qty):
                $prod = $products[$pid];
                $subtotal = $prod['price'] * $qty;
                $total += $subtotal;
        ?>
            <div class="cart-item">
                <img src="<?php echo htmlspecialchars($prod['image']); ?>" class="product-image" style="width:80px;height:80px;">
                <div class="product-title"><?php echo htmlspecialchars($prod['name']); ?> x <?php echo $qty; ?></div>
                <div class="product-price">R<?php echo $subtotal; ?></div>
                <form method="post" style="margin-top:10px;">
                    <input type="hidden" name="product_id" value="<?php echo $pid; ?>">
                    <button type="submit" name="remove_from_cart" class="add-to-cart-btn">Remove</button>
                </form>
            </div>
        <?php
            endforeach;
        else:
            echo "<p>Shopping cart is empty.</p>";
        endif;
        ?>
    </div>
    <?php if (!empty($_SESSION['cart'])): ?>
        <div class="pay-section">
            <h4>Total: R<?php echo $total; ?></h4>
            <a href="?page=payment" class="add-to-cart-btn" style="text-decoration:none;display:inline-block;">Proceed to Payment</a>
        </div>
    <?php endif; ?>
<?php
// PAYMENT PAGE
elseif ($page === 'payment' && isset($_SESSION['user'])):
    $total = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $pid => $qty) {
            $prod = $products[$pid];
            $subtotal = $prod['price'] * $qty;
            $total += $subtotal;
        }
    }
?>
    <h2>Payment</h2>
    <?php if ($total > 0): ?>
        <div class="cart">
            <?php foreach ($_SESSION['cart'] as $pid => $qty): 
                $prod = $products[$pid];
                $subtotal = $prod['price'] * $qty;
            ?>
                <div class="cart-item">
                    <img src="<?php echo htmlspecialchars($prod['image']); ?>" class="product-image" style="width:80px;height:80px;">
                    <div class="product-title"><?php echo htmlspecialchars($prod['name']); ?> x <?php echo $qty; ?></div>
                    <div class="product-price">R<?php echo $subtotal; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="pay-section">
            <h4>Total: R<?php echo $total; ?></h4>
            <form method="post">
                <button type="submit" name="pay" class="add-to-cart-btn">Pay Now</button>
            </form>
        </div>
    <?php else: ?>
        <p>Your cart is empty. <a href="?page=dashboard">Go back to dashboard</a></p>
    <?php endif; ?>
<?php
else:
    echo "<p>Page not found or access denied.</p>";
endif;
?>
</div>
<footer>
    &copy; <?php echo date('Y'); ?> K&M Retail Store. All rights reserved.
</footer>
</body>
</html>
