<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
$fullname = $_SESSION['fullname'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Scanner</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        background-color: #4A3AFF;
        color: white;
        padding: 10px;
        height: 100vh;
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 0;
        left: 0;
        bottom: 0;
        overflow-y: auto;
    }

    .sidebar img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        margin-bottom: 60px;
    }

    .sidebar h1 {
        font-size: 24px;
        margin: 10px 0;
    }

    .sidebar a {
        color: white;
        text-decoration: none;
        font-size: 15px;
        margin-bottom: 50px;
        display: block;
    }

    .sidebar a:hover {
        text-decoration: underline;
    }

    .sidebar .bottom-links a {
        display: flex;
        align-items: center;
        margin-top: 10px;
    }

    .sidebar .bottom-links a i {
        margin-right: 10px;
    }

    .main-content {
        flex-grow: 1;
        background-color: #F5F5F5;
        padding: 20px;
        margin-left: 250px;
        transition: margin-left 0.3s ease;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        margin-left: 1000px;
    }

    .header .user-info {
        display: flex;
        align-items: left;
    }

    .header .user-info .notification {
        display: flex;
        align-items: center;
    }

    .header .user-info .vertical-rule {
        border-left: 1px solid #E0E0E0;
        height: 40px;
        margin: 0 20px;
    }

    .header .user-info img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 10px;
    }

    .header .user-info span {
        font-size: 16px;
    }

    .header hr {
        width: 100%;
        border: 0;
        border-top: 1px solid #E0E0E0;
        margin: 10px 0;
    }

    .container {
        margin-top: 30px;
    }

    /* Modal Content Styling */
.modal-content {
    border-radius: 10px; /* Rounded corners */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow */
}

.modal-header {
    background-color: #f8f9fa; /* Light background for the header */
    color: #333; /* Dark text for the title */
    padding-bottom: 15px; /* Space between the title and body */
}

.modal-title {
    font-size: 1.5rem; /* Larger title */
    font-weight: 600;
}

.modal-body {
    padding-top: 20px;
}

h5 {
    font-weight: 500;
    font-size: 1.2rem;
    color: #495057; /* Darker color for headings */
}

p {
    font-size: 1rem;
    color: #6c757d; /* Light gray text */
}

button.btn-primary {
    background-color: #007bff;
    border: none;
    font-size: 1rem;
    padding: 10px 20px;
    border-radius: 5px;
}

button.btn-primary:hover {
    background-color: #0056b3; /* Darker shade for hover effect */
}

/* Authorized Persons Styling */
#authorizedPersonsContainer {
    max-height: 300px; /* Limit the height for scrolling */
    overflow-y: auto;
}

#authorizedPersonsContainer .d-flex {
    align-items: center;
    margin-bottom: 15px;
}

#authorizedPersonsContainer img {
    border-radius: 50%;
    margin-right: 15px;
}

#authorizedPersonsContainer .ml-3 {
    flex: 1;
}

#authorizedPersonsContainer h6 {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 5px;
}

#authorizedPersonsContainer p {
    font-size: 0.9rem;
    color: #6c757d;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .modal-dialog {
        max-width: 90%; /* Modal takes up more space on smaller screens */
    }

    .modal-body {
        padding: 20px; /* Padding for better spacing on mobile */
    }

    .col-md-6 {
        margin-bottom: 30px; /* Margin between columns on mobile */
    }

    #authorizedPersonsContainer {
        max-height: 200px; /* Reduce height on smaller screens */
    }
}

    @media (max-width: 768px) {
        .sidebar {
            width: 200px;
        }

        .main-content {
            margin-left: 200px;
            width: calc(100% - 200px);
        }
    }

    @media (max-width: 576px) {
        .sidebar {
            width: 100%;
            flex-direction: row;
            justify-content: space-around;
            padding: 10px 0;
            height: auto;
        }

        .main-content {
            margin-left: 0;
            width: 100%;
        }
    }
    @media (max-width: 576px) {
    #childImage, #authorizedImage {
        max-width: 100px;  /* Smaller images on mobile */
    }
}

</style>

<div class="sidebar">
    <img src="logo/logo.png" alt="Logo">
    <a href="qr_scanner.php">Scanner</a>
    <a href="S_records.php">Student Records</a>
    <a href="P_records.php">Parent Records</a>
    <a href="U_records.php">Pick up Records</a>
    <div class="bottom-links">
        <a href="#">
            <i class="fas fa-cog"></i> Settings
        </a>
        <a href="logout.php">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="main-content">
    <div class="header">
        <div class="user-info">
            <div class="notification">
                <i class="fas fa-bell"></i>
            </div>
            <hr>
            <span class="fullname"><?php echo htmlspecialchars($fullname); ?></span>
            <span class="role"><?php echo htmlspecialchars($role); ?></span>
            <img src="logo/user_avatar.png" alt="User Avatar">
        </div>
    </div>

    <div class="container">
        <h2 class="text-center">QR Code Scanner</h2>
        <div id="reader" style="width: 100%; height: 500px; background: #f3f3f3; text-align: center;">
            <video id="videoElement" style="width: 100%; height: 100%; object-fit: cover;"></video>
        </div>

        <!-- Modal Content -->
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header border-bottom-0">
                <h5 class="modal-title" id="infoModalLabel">Child and Authorized Person Info</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Child Information -->
                    <div class="col-md-6 text-center mb-4">
                    <div id="student_id" style="display: none;"></div>

                        <img id="childImage" class="img-fluid rounded-circle" style="max-width: 200px;" alt="Child Image" />
                        <h5 class="mt-3" id="childName"></h5>
                        <p><strong>Teacher:</strong> <span id="teacherName"></span></p>
                        <p><strong>Age:</strong> <span id="childAge"></span></p>
                        <p><strong>Grade:</strong> <span id="childGrade"></span></p>
                        <p><strong>Section:</strong> <span id="childSection"></span></p>
                        <p><strong>Address:</strong> <span id="childAddress"></span></p>
                    </div>

                    <!-- Authorized Person Information -->
                    <div class="col-md-6 mb-4">
                        <h5 class="text-center">Authorized Person(s)</h5>
                        <div id="authorizedPersonsContainer">
                            <!-- Authorized persons will be dynamically inserted here -->
                        </div>
                    </div>
                </div>
                <hr>
            </div>
            <div class="modal-footer">
            
            <button type="button" class="btn btn-primary" onclick="timeIn()">Time In</button>
            <button type="button" class="btn btn-primary" onclick="timeOut()">Time Out</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal to select authorized person -->
<div id="authorizedPersonModal" class="modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Select Authorized Person</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- The dropdown will be inserted here -->
        <div id="authorizedPersonDropdown"></div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="saveAuthorizedPersonBtn">Save</button>
      </div>
    </div>
  </div>
</div>


<script>
let videoElement = document.getElementById('videoElement');
const canvas = document.createElement("canvas");
const context = canvas.getContext("2d", { willReadFrequently: true });

// Start the QR scanner
function startScanner() {
    navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } })
        .then(function(stream) {
            videoElement.srcObject = stream;
            videoElement.setAttribute("playsinline", true);
            videoElement.play();
            console.log("Camera access granted, starting scanner.");
            requestAnimationFrame(scanQRCode);  // Now calling the scanQRCode function
        })
        .catch(function(error) {
            alert("Error accessing camera: " + error);
            console.error("Camera error:", error);
        });
}

// The missing scanQRCode function that scans the QR code from the video stream
// The scanQRCode function that continuously scans the QR code from the video stream
let isScanning = false;  // Flag to track scanning status

function scanQRCode() {
    if (videoElement.readyState === videoElement.HAVE_ENOUGH_DATA) {
        // If already scanning, don't proceed further
        if (isScanning) {
            requestAnimationFrame(scanQRCode);
            return;
        }

        // Set the canvas size equal to the video size
        canvas.height = videoElement.videoHeight;
        canvas.width = videoElement.videoWidth;

        // Draw the current video frame to the canvas
        context.drawImage(videoElement, 0, 0, canvas.width, canvas.height);

        // Attempt to decode the QR code from the canvas
        const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
        const code = jsQR(imageData.data, canvas.width, canvas.height);

        if (code) {
            // QR code found, handle it and stop further scanning for now
            console.log("QR Code detected:", code.data);
            handleQRCode(code.data);  // Pass the QR data to the handler function

            // Set the scanning flag to true to prevent scanning multiple QR codes at once
            isScanning = true;
            
            // Optionally, you can set a timeout here to reset the flag after a certain time,
            // for example, in case the user wants to scan a new code after a brief pause.
            setTimeout(() => {
                isScanning = false;
            }, 1000);  // Allow scanning again after 1 second (adjust as needed)
        }

        // Continue scanning (whether QR code is detected or not)
        requestAnimationFrame(scanQRCode);
    } else {
        // If video not ready, keep trying to scan
        requestAnimationFrame(scanQRCode);
    }
}



// Handle the QR Code scanning process
function handleQRCode(qrCodeMessage) {
            console.log("Scanned QR Code: ", qrCodeMessage);
            try {
                const url = new URL(qrCodeMessage);
                const params = new URLSearchParams(url.search);
                const student_id = params.get("student_id");
                const authorized_id = params.get("authorized_id");

                if (student_id && authorized_id) {
                    // Fetch child info from the server using the IDs
                    fetchChildInfo(student_id, authorized_id);
                } else {
                    alert("Invalid QR Code.");
                }
            } catch (error) {
                console.error("Error processing QR Code: ", error);
                alert("Invalid QR Code.");
            }
        }

        let globalStudentId = '';  // Declare a global variable to hold the student ID

// Function to fetch child info based on student_id and authorized_id
function fetchChildInfo(student_id, authorized_id) {
    $.get("fetch_data.php", { student_id: student_id, authorized_id: authorized_id }, function(data) {
        if (data.success) {
            const child_info = data.child_info;
            const authorized_info = data.authorized_info;

            // Populate the modal with fetched data
            document.getElementById("childName").innerText = child_info.name;
            document.getElementById("teacherName").innerText = child_info.teacher;
            document.getElementById("childAge").innerText = child_info.age;
            document.getElementById("childGrade").innerText = child_info.grade;
            document.getElementById("childSection").innerText = child_info.section;
            document.getElementById("childAddress").innerText = child_info.address;
            document.getElementById("childImage").src = child_info.image || 'default-child-image.jpg';

            // Add the student ID to the global variable
            globalStudentId = data.student_id;  // Store student_id in the global variable
            console.log("Student ID populated globally: ", globalStudentId); // Debug log

            // Populate authorized person information
            const container = document.getElementById("authorizedPersonsContainer");
            container.innerHTML = ""; // Clear existing content

            if (authorized_info) {
                const personDiv = document.createElement("div");
                personDiv.classList.add("d-flex", "align-items-center", "mb-3");

                const personImage = document.createElement("img");
                personImage.classList.add("img-fluid", "rounded-circle");
                personImage.style.maxWidth = "50px";
                personImage.src = authorized_info.image || 'default-authorized-image.jpg';

                const personInfo = document.createElement("div");
                personInfo.classList.add("ml-3");
                personInfo.innerHTML = `
                    <h6>${authorized_info.fullname}</h6>
                    <p><strong>Authorized ID:</strong> ${authorized_info.id}</p>
                `;

                personDiv.appendChild(personImage);
                personDiv.appendChild(personInfo);

                container.appendChild(personDiv);
            }

            // Show the modal
            $('#infoModal').modal('show');
        } else {
            alert(data.message || "Failed to fetch child information.");
        }
    }).fail(function() {
        alert("Error in fetching data from the server.");
    });
}

// Time-In Function to send the student_id
async function timeIn() {
    console.log("Time in button clicked");

    // Check if the global studentId is available
    if (!globalStudentId) {
        alert("Student ID is required.");
        return;
    }

    // Log the global student ID
    console.log("Student ID from global variable: ", globalStudentId);

    try {
        // Send the student_id as JSON to the server
        const response = await fetch('http://localhost/capstone-main/attendance_action.php', {
            method: 'POST',
            body: JSON.stringify({ studentId: globalStudentId }), // Send student_id as JSON
            headers: {
                'Content-Type': 'application/json'
            }
        });

        const result = await response.json();

        if (response.ok) {
            console.log("Success:", result);
            alert(result.message); // Show success message
            $('#infoModal').modal('hide'); // Close the modal
        } else {
            console.error("Error:", result.message);
            alert(result.message); // Show error message
        }
    } catch (error) {
        console.error("Error submitting data:", error);
        alert("Error submitting time-in data. Please try again.");
    }
}

async function timeOut() {
    console.log("Time out button clicked");

    // Check if the global studentId is available
    if (!globalStudentId) {
        alert("Student ID is required.");
        return;
    }

    // Log the global student ID
    console.log("Student ID from global variable: ", globalStudentId);

    try {
        // Fetch the authorized persons for this student ID
        const response = await fetch('fetch_authorized_persons.php', {
            method: 'POST',
            body: JSON.stringify({ studentId: globalStudentId }),
            headers: {
                'Content-Type': 'application/json'
            }
        });

        if (!response.ok) {
            const errorText = await response.text(); // Get raw response text if error occurs
            console.error("Error fetching authorized persons:", errorText);
            alert("Error fetching authorized persons. Please try again.");
            return;
        }

        const resultText = await response.text(); // Get response as text to safely parse JSON
        let result;
        try {
            result = JSON.parse(resultText);
        } catch (error) {
            console.error("Invalid JSON response:", resultText);
            alert("Invalid response format from the server.");
            return;
        }

        // Check if we got the authorized persons
        if (result && result.authorizedPersons) {
            // Create a dropdown of authorized persons
            let dropdownHtml = '<select id="authorizedPersonSelect" class="form-control">';
            dropdownHtml += '<option value="">Select Authorized Person</option>';

            // Populate the dropdown with authorized persons
            result.authorizedPersons.forEach(person => {
                dropdownHtml += `<option value="${person.id}">${person.fullname}</option>`;
            });
            dropdownHtml += '</select>';

            // Show the dropdown in a modal or somewhere on the page
            document.getElementById('authorizedPersonDropdown').innerHTML = dropdownHtml;

            // Show the modal for the user to select an authorized person
            $('#authorizedPersonModal').modal('show');

            // Wait for user selection and handle the 'Save' button click
            document.getElementById('saveAuthorizedPersonBtn').addEventListener('click', async function () {
                const authorizedId = document.getElementById('authorizedPersonSelect').value;

                // If no selection is made, return
                if (!authorizedId) {
                    alert("Authorized person is required.");
                    return;
                }

                // Log the selected authorized person ID
                console.log("Authorized person selected:", authorizedId);

                try {
                    // Send the student_id and authorized_person_id to the server
                    const updateResponse = await fetch('http://localhost/capstone-main/attendance_timeout.php', {  // Updated URL
                        method: 'POST',
                        body: JSON.stringify({
                            studentId: globalStudentId,
                            authorizedId: authorizedId // Send the selected authorized person ID
                        }),
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    // Parse response from the server
                    const updateResultText = await updateResponse.text();
                    let updateResult;
                    try {
                        updateResult = JSON.parse(updateResultText);
                    } catch (error) {
                        console.error("Invalid JSON response from update:", updateResultText);
                        alert("Error processing the request. Please try again.");
                        return;
                    }

                    if (updateResponse.ok) {
                        console.log("Success:", updateResult);
                        alert(updateResult.message); // Show success message
                        $('#authorizedPersonModal').modal('hide'); // Close the modal
                    } else {
                        console.error("Error:", updateResult.message);
                        alert(updateResult.message); // Show error message
                    }

                } catch (error) {
                    console.error("Error submitting data:", error);
                    alert("Error submitting time-out data. Please try again.");
                }
            });

        } else {
            alert("No authorized persons found.");
        }
    } catch (error) {
        console.error("Error fetching data:", error);
        alert("Error fetching authorized persons. Please try again.");
    }
}








window.onload = function() {
    startScanner();
};


</script>


<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
