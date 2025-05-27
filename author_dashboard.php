<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'author') {
    die("User not logged in or unauthorized.");
}

$author_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, title, dateposted, status FROM news WHERE author_id = ? ORDER BY dateposted DESC");
$stmt->bind_param("i", $author_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Author Dashboard</title>
    <style>
        body {
            font-family: 'Tahoma', sans-serif;
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
            background-color: #f0f8ff;
            direction: ltr;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 30px;
            text-align: center;
        }
        a.add-news-btn {
            display: inline-block;
            background-color: #27ae60;
            color: #fff;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 6px;
            text-decoration: none;
            margin-bottom: 25px;
            transition: background-color 0.3s ease;
            float: left;
        }
        a.add-news-btn:hover {
            background-color: #219150;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 15px 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #2980b9;
            color: white;
        }
        tbody tr:hover {
            background-color: #f5f7fa;
        }
        p.no-news {
            font-size: 18px;
            color: #555;
            text-align: center;
            margin-top: 40px;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <h1>Author Dashboard</h1>
    <div class="clearfix">
        <a href="add_news.php" class="add-news-btn">Add New News Item</a>
    </div>
    <h2>Your News Items</h2>
    <?php if ($result->num_rows > 0): ?>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Date Posted</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['dateposted']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
    <p class="no-news">You have not added any news items yet.</p>
    <?php endif; ?>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>

