<?php
$servername = "sql105.infinityfree.com"; // Your MySQL server address
$username = "if0_37210200"; // Your MySQL username
$password = "oUFeys6EfjVXz1"; // Your MySQL password
$dbname = "if0_37210200_XXX"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update visit count
$sql = "UPDATE visit_count SET count = count + 1 WHERE id = 1";

if ($conn->query($sql) === TRUE) {
    // Fetch the updated count
    $result = $conn->query("SELECT count FROM visit_count WHERE id = 1");
    $row = $result->fetch_assoc();
    echo $row['count'];
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>
