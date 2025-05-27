<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'editor') {
    die("Unauthorized access.");
}

// Fetch all news items by authors ordered by dateposted DESC
$sql = "SELECT news.id, news.title, news.dateposted, news.status, user.name AS author_name FROM news JOIN user ON news.author_id = user.id ORDER BY news.dateposted DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Editor Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; background: #f9f9f9;}
    h1 { color: #333; text-align: center; }
    table { border-collapse: collapse; width: 100%; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #007BFF; color: white; }
    tr:nth-child(even) { background: #f2f2f2; }
    a.action-btn { margin-right: 8px; padding: 6px 12px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
    a.approve { background: #28a745; }
    a.deny { background: #dc3545; }
    a.delete { background: #6c757d; }
</style>
</head>
<body>
<h1>Editor Dashboard</h1>
<table>
    <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Date Posted</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['title']); ?></td>
                <td><?php echo htmlspecialchars($row['author_name']); ?></td>
                <td><?php echo htmlspecialchars($row['dateposted']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['status'])); ?></td>
                <td>
                    <a class="action-btn approve" href="approve_news.php?id=<?php echo $row['id']; ?>">Approve</a>
                    <a class="action-btn deny" href="deny_news.php?id=<?php echo $row['id']; ?>">Deny</a>
                    <a class="action-btn delete" href="delete_news.php?id=<?php echo $row['id']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile;
        else: ?>
            <tr><td colspan="5" style="text-align:center;">No news items found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>

<?php $conn->close(); ?>
