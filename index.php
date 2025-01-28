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
        <nav class="w-full flex justify-between items-center px-6 py-3">
            <span class="text-xl font-bold">LOGO</span>
            <div>
            <a href="signup.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full">Signup</a>
            <a href="login.php" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-full">Login</a>
            </div>
        </nav>
    </header>

    <!-- Items Table -->
    <section class="py-8">
            <h2 class="text-center text-2xl font-bold text-gray-700 mb-6">ITEMS</h2>
        <div class="container mx-auto px-4">
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
                        <?php
                        if ($products->num_rows > 0) {
                            while ($product = $products->fetch_assoc()) {
                                echo "<tr class='border-b hover:bg-gray-50'>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($product['product_name']) . "</td>";
                                echo "<td class='px-6 py-4'>₦" . htmlspecialchars($product['price']) . "</td>";
                                echo "<td class='px-6 py-4'>" . htmlspecialchars($product['interest']) . "%</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3' class='px-6 py-4 text-center'>No products found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        <ul class="inline-flex space-x-2">
            <?php if ($page > 1): ?>
                <li><a href="index.php?page=<?= $page - 1 ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Previous</a></li>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li>
                    <a href="index.php?page=<?= $i ?>" 
                       class="px-3 py-1 <?= $page == $i ? 'bg-blue-500 text-white' : 'bg-gray-300' ?> rounded hover:bg-blue-400">
                        <?= $i ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <li><a href="index.php?page=<?= $page + 1 ?>" class="px-3 py-1 bg-gray-300 rounded hover:bg-gray-400">Next</a></li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-400 py-4 mt-8 text-center">
        <p>&copy; 2025 GPAS. All Rights Reserved.</p>
    </footer>
</body>
</html>
