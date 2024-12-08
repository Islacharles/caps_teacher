<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include database connection
include 'connection.php';

// Get student_id and authorized_id from the URL parameters
$student_id = isset($_GET['student_id']) ? trim($_GET['student_id']) : null;
$authorized_id = isset($_GET['authorized_id']) ? trim($_GET['authorized_id']) : null;

// Initialize response array
$response = [
    'success' => false,
    'message' => 'Invalid data.',
    'child_info' => null,
    'authorized_info' => null
];

// Function to convert image to Base64
function imageToBase64($imageData) {
    if ($imageData) {
        return 'data:image/jpeg;base64,' . base64_encode($imageData);
    }
    return null;
}

// Fetch child and authorized person data from the database
if ($student_id && $authorized_id) {
    $sql = "
        SELECT 
            c.child_name, 
            c.child_age, 
            c.child_grade, 
            c.child_section, 
            c.child_address, 
            c.child_image, 
            a.fullname AS teacher_name, 
            auth.id AS authorized_id, 
            auth.fullname AS authorized_name,
            auth.authorized_image
        FROM 
            child_acc c
        LEFT JOIN 
            admin_staff a ON c.child_teacher = a.id 
        LEFT JOIN 
            authorized_persons auth ON c.authorized_id = auth.id
        WHERE 
            c.student_id = ? AND auth.id = ?
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $student_id, $authorized_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        // Fetch the row of data
        $row = $result->fetch_assoc();

        // Prepare the response
        $response['success'] = true;
        $response['message'] = 'Data fetched successfully.';
        $response['student_id'] = $student_id;  // Add the student_id to the response
        $response['child_info'] = [
            'name' => $row['child_name'],
            'age' => $row['child_age'],
            'grade' => $row['child_grade'],
            'section' => $row['child_section'],
            'address' => $row['child_address'],
            'teacher' => $row['teacher_name'],
            'image' => $row['child_image'] ? imageToBase64($row['child_image']) : null // Convert image BLOB to base64
        ];
        
        // Add the authorized person info (only one record expected)
        $response['authorized_info'] = [
            'id' => $row['authorized_id'],
            'fullname' => $row['authorized_name'],
            'image' => $row['authorized_image'] ? imageToBase64($row['authorized_image']) : null // Convert image BLOB to base64
        ];
    } else {
        $response['message'] = 'No data found for the given student_id and authorized_id.';
    }

    $stmt->close();
}

$conn->close();

// Return the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
exit;
?>
