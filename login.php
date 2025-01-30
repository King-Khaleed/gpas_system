<?php
// Start a session
session_start();

// Connect to the database
$conn = new mysqli('localhost', 'root', '', 'gpas');

// Check if there are any errors connecting to the database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Find the user in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php
        // Get messages
        if(isset($_GET['message'])) {
            $message = $_GET['message'] ?? 'welcome';
            echo "<script> alert('".  $message . "') </script>";    
        }
    ?>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4">Log In</h1>
        <form action="" method="POST" class="bg-white shadow-md rounded p-4">
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="w-full border rounded p-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Log In</button>
        </form>
    </div>
</body>
</html>
