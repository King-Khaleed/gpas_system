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
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);


    // Check the if user has an account

    // Find the user in the database
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
            $msg = "You already have an account!";
            header("Location: login.php?message=" . $msg);
            exit;
    }


    // Insert user into the database
    $sql = "INSERT INTO users (name, email, password) VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql)) {
        $msg = "Account created successfully";

        // Redirect if user is not logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: dashboard.php?message=" . $msg);
            exit;
        }        
    } else {
        echo "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
        <h1 class="text-3xl font-bold mb-4">Sign Up</h1>
        <form action="" method="POST" class="bg-white shadow-md rounded p-4">
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Name</label>
                <input type="text" name="name" id="name" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" required class="w-full border rounded p-2">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" name="password" id="password" required class="w-full border rounded p-2">
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Sign Up</button>
        </form>
    </div>
</body>
</html>
