<?php
session_start();
$conn = new mysqli('localhost', 'root', '', 'MYDB');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: editor_dashboard.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID not provided.";
}

$conn->close();
?>
