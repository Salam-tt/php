<?php
// login.php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password, role FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $role);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Store user ID and role in session
            $_SESSION['user_id'] = $id;
            $_SESSION['role'] = $role;

            // Redirect based on role
            switch ($role) {
                case 'author':
                    header("Location: author_dashboard.php");
                    break;
                case 'editor':
                    header("Location: editor_dashboard.php");
                    break;
                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;
                default:
                    echo "Unknown role.";
                    exit();
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "User not found.";
    }
}
?>

<!-- Login form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST" action="">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" value="Login">
    </form>
</body>
</html>
