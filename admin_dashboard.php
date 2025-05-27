<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die("Unauthorized access.");
}

// Fetch all users
$sql = "SELECT id, name, email, role FROM user ORDER BY name ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Admin Dashboard</title>
<style>
    body { font-family: Arial, sans-serif; max-width: 900px; margin: 40px auto; padding: 20px; background: #f9f9f9;}
    h1 { color: #333; text-align: center; }
    table { border-collapse: collapse; width: 100%; background: white; }
    th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
    th { background-color: #343a40; color: white; }
    tr:nth-child(even) { background: #f2f2f2; }
</style>
</head>
<body>
<h1>Admin Dashboard</h1>
<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && $result->num_rows > 0):
            while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td><?php echo htmlspecialchars(ucfirst($row['role'])); ?></td>
            </tr>
        <?php endwhile;
        else: ?>
            <tr><td colspan="3" style="text-align:center;">No users found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</body>
</html>

<?php $conn->close(); ?>
