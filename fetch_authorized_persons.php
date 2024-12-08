<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);
$studentId = $data['studentId']; // Get studentId from the request body

// Database connection
include 'connection.php';

// Ensure connection was successful
if ($conn->connect_error) {
    echo json_encode(["message" => "Failed to connect to the database."]);
    exit;
}

// Query to get authorized persons based on student ID
$query = "SELECT ap.id, ap.fullname 
          FROM authorized_persons ap
          INNER JOIN child_acc ca ON ap.id = ca.authorized_id
          WHERE ca.student_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $studentId);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results
$authorizedPersons = [];
while ($row = $result->fetch_assoc()) {
    $authorizedPersons[] = $row;
}

// Check if any authorized persons were found
if (empty($authorizedPersons)) {
    echo json_encode(["message" => "No authorized persons found."]);
} else {
    // Send the data back as JSON
    echo json_encode(["authorizedPersons" => $authorizedPersons]);
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
