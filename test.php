<?php
// 1. Connect to the database
$conn = new mysqli('localhost', 'root', '', 'gpas');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Set items per page
$items_per_page = 5;

// 3. Find the current page number
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Default to page 1
if ($page < 1) $page = 1; // Prevent negative or invalid pages
$offset = ($page - 1) * $items_per_page; // Calculate offset

// 4. Get total items
$result = $conn->query("SELECT COUNT(*) AS total FROM products");
$total_items = $result->fetch_assoc()['total'];
$total_pages = ceil($total_items / $items_per_page); // Total number of pages

// 5. Fetch the current page items
$sql = "SELECT * FROM products LIMIT $items_per_page OFFSET $offset";
$products = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto py-8">
        <h1 class="text-2xl font-bold mb-6">Product Table</h1>
        <table class="table-auto border-collapse border border-gray-300 w-full text-sm">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border border-gray-300 px-4 py-2">Product Name</th>
                    <th class="border border-gray-300 px-4 py-2">Price</th>
                    <th class="border border-gray-300 px-4 py-2">Interest</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($products->num_rows > 0) {
                    while ($product = $products->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($product['product_name']) . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($product['price']) . "</td>";
                        echo "<td class='border border-gray-300 px-4 py-2'>" . htmlspecialchars($product['interest']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' class='border border-gray-300 px-4 py-2 text-center'>No products found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="mt-4 flex justify-between items-center">
            <?php if ($page > 1): ?>
                <a href="index.php?page=<?= $page - 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Previous</a>
            <?php endif; ?>

            <?php if ($page < $total_pages): ?>
                <a href="index.php?page=<?= $page + 1 ?>" class="bg-blue-500 text-white px-4 py-2 rounded">Next</a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
