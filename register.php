<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'MYDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO user (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "Registration successful. You can now <a href='login.php'>log in</a>.";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h1>Register</h1>
    <form method="POST">
        <label for="name">Name:</label>
        <input type="text" name="name" required><br>
        <label for="email">Email:</label>
        <input type="email" name="email" required><br>
        <label for="password">Password:</label>
        <input type="password" name="password" required><br>
        <label for="role">Role:</label>
        <select name="role" required>
            <option value="author">Author</option>
            <option value="editor">Editor</option>
            <option value="admin">Admin</option>
        </select><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>

<?php $conn->close(); ?>
