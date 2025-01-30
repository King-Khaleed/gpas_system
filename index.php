<?php
// 1. Connect to the database
$conn = new mysqli('localhost', 'root', '', 'gpas');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Set items per page
$items_per_page = 5;

// 3. Find the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $items_per_page;

// 4. Get total items
$result = $conn->query("SELECT COUNT(*) AS total FROM products");
$total_items = $result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page);

// 5. Fetch the current page items
$sql = "SELECT * FROM products LIMIT $items_per_page OFFSET $offset";
$products = $conn->query($sql);

require_once("./config/functions.php");
$total_product_counts = countAllProducts();
$total_price = sumAllProductPrice();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPAS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans">
    <!-- Header -->
    <header class="bg-blue-600 text-white">
        <nav class="w-full flex flex-col md:flex-row justify-between items-center px-6 py-3">
            <div class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
                <h1 class="text-2xl font-bold text-red-500">
                    <a href="index.php">GPAS</a>
                </h1>
                <div>
                    <a href="dashboard.php" class="text-xl font-bold">Dashboard</a>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <a href="signup.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full">Signup</a>
                <a href="login.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full ml-2">Login</a>
            </div>
        </nav>
    </header>

    <!-- Summary Section -->
    <section class="flex flex-col md:flex-row justify-evenly bg-gray-200">
        <div class="py-10 flex flex-col md:flex-row items-center justify-around">
            <div class="bg-white shadow-lg rounded-lg p-6 w-60 text-center mb-4 md:mb-0">
                <h2 class="text-2xl font-semibold mb-2 text-green-600">Total Products</h2>
                <p class="text-3xl font-bold"><?php echo $total_product_counts; ?></p>
            </div>
            <div class="bg-white shadow-lg rounded-lg p-6 w-60 text-center">
                <h2 class="text-2xl font-semibold mb-2 text-green-600">Total Price</h2>
                <p class="text-3xl font-bold">₦<?php echo number_format($total_price, 2); ?></p>
            </div>
        </div>
    </section>

    <!-- Items Table -->
    <section class="py-8">
        <div class="container mx-auto px-4">
            <h2 class="text-center text-2xl font-bold text-gray-700 mb-6">ITEMS</h2>
            <div class="overflow-x-auto bg-white shadow rounded-lg">
                <table class="w-full text-sm text-left text-gray-700">
                    <thead class="bg-gray-100 text-gray-800 text-xs uppercase">
                        <tr>
                            <th class="px-6 py-3">Product Name</th>
                            <th class="px-6 py-3">Price</th>
                            <th class="px-6 py-3">Interest</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($products->num_rows > 0): ?>
                            <?php while ($product = $products->fetch_assoc()): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($product['product_name']); ?></td>
                                    <td class="px-6 py-4">₦<?php echo htmlspecialchars($product['product_price']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($product['interest_rate']); ?>%</td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-gray-500">No products found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        <nav class="inline-flex rounded-md shadow">
            <?php if ($page > 1): ?>
                <a href="index.php?page=<?php echo $page - 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-l-md hover:bg-blue-600">Previous</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="index.php?page=<?php echo $i; ?>" class="px-4 py-2 bg-white border-t border-b border-gray-200 text-blue-500 hover:bg-gray-100 <?php echo ($i == $page) ? 'font-bold bg-gray-100' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="index.php?page=<?php echo $page + 1; ?>" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">Next</a>
            <?php endif; ?>
        </nav>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-4 mt-8 text-center">
        <p>&copy; 2025 GPAS. All Rights Reserved.</p>
    </footer>
</body>
</html>