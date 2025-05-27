<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
    $author_id = $_SESSION['user_id'];

    // Validate required fields
    if ($title === '' || $body === '' || $category_id <= 0) {
        echo "Please fill in all required fields including selecting a valid category.";
    } else {
        // Validate category exists
        $stmt_cat = $conn->prepare("SELECT id FROM category WHERE id = ?");
        $stmt_cat->bind_param("i", $category_id);
        $stmt_cat->execute();
        $result_cat = $stmt_cat->get_result();
        if ($result_cat->num_rows !== 1) {
            echo "Please select a valid category.";
        } else {
            $stmt_cat->close();
            // Insert news
            $stmt = $conn->prepare("INSERT INTO news (title, body, category_id, author_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssii", $title, $body, $category_id, $author_id);
            if ($stmt->execute()) {
                $stmt->close();
                header("Location: author_dashboard.php");
                exit();
            } else {
                echo "Error inserting news: " . $stmt->error;
            }
        }
    }
}

// Fetch categories for the dropdown
$category_sql = "SELECT * FROM category ORDER BY name";
$category_result = $conn->query($category_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Add News Item</title>
</head>
<body>
    <h1>Add News Item</h1>
    <form method="POST" action="">
        <label for="title">Title:</label><br>
        <input type="text" name="title" required><br><br>

        <label for="body">Body:</label><br>
        <textarea name="body" rows="10" cols="50" required></textarea><br><br>

        <label for="category_id">Category:</label><br>
        <select name="category_id" required>
            <option value="">-- Select Category --</option>
            <?php
            if ($category_result && $category_result->num_rows > 0) {
                while ($category = $category_result->fetch_assoc()) {
                    echo '<option value="'.htmlspecialchars($category['id']).'">'.htmlspecialchars($category['name']).'</option>';
                }
            } else {
                echo '<option value="">No categories available</option>';
            }
            ?>
        </select><br><br>

        <input type="submit" value="Add News Item">
    </form>
</body>
</html>

<?php $conn->close(); ?>
