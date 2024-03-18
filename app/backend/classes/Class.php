<?php
// Turn on error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Start the session
session_start();

// Check if the session contains user_id
if (!isset($_SESSION['user_id'])) {
    die('User ID not found in session');
}

// Get the user_id from the session
$user_id = $_SESSION['user_id'];
echo "User ID: $user_id<br>";

// Connect to the database
$db = new mysqli('localhost', 'username', 'password', 'database');

if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Prepare the SQL statement
$stmt = $db->prepare("
    SELECT classes.* 
    FROM classes 
    JOIN class_students ON classes.class_id = class_students.class_id 
    WHERE class_students.student_id = ?
");

if (!$stmt) {
    die("Prepare failed: " . $db->error);
}

// Bind the user_id parameter to the SQL statement
if (!$stmt->bind_param('i', $user_id)) {
    die("Bind failed: " . $stmt->error);
}

// Execute the SQL statement
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

// Get the result
$result = $stmt->get_result();

// Check if the query returns any results
if ($result->num_rows === 0) {
    die('No classes found for this user');
}

// Fetch all the classes
$classes = $result->fetch_all(MYSQLI_ASSOC);

// Close the statement and the database connection
$stmt->close();
$db->close();

// Now $classes contains all the classes the student is in
foreach ($classes as $class) {
    echo "Class: " . $class['name'] . "<br>";
}
?>