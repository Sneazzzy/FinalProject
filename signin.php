<?php
// Suppress all error reporting to ensure a clean JSON output.
error_reporting(0);
ini_set('display_errors', 0);

// Set the content type to JSON for all responses from this script.
header('Content-Type: application/json');

// Start the session to store user login status.
session_start();

// --- Configuration ---
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'final_project';
$home_url = 'home.html';

// --- Response Array ---
// We will build this array and encode it as JSON at the end.
$response = [];

// --- Database Connection ---
$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    $response = ['status' => 'error', 'message' => 'Database connection failed.'];
    echo json_encode($response);
    exit();
}

// --- Main Logic ---
// Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['emailOrnum']) && isset($_POST['password'])) {
        $mail = trim($_POST['emailOrnum']);
        $pass = trim($_POST['password']);

        if (empty($mail) || empty($pass)) {
            $response = ['status' => 'error', 'message' => '⚠️ All fields are required.'];
        } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL) && !ctype_digit($mail)) {
            $response = ['status' => 'error', 'message' => '⚠️ Please enter a valid email or phone number format.'];
        } else {
            // If initial validation passes, proceed to check credentials
            $hashed_password = hash('sha256', $pass);
            
            $sql = "SELECT email, phone_num, password_hash FROM users WHERE (email = ? OR phone_num = ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $mail, $mail);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user_data = $result->fetch_assoc();
                if ($hashed_password === $user_data['password_hash']) {
                    // SUCCESS CASE
                    $_SESSION['user'] = $user_data['email'];
                    $response = ['status' => 'success', 'redirect' => $home_url];
                } else {
                    // Password mismatch
                    $response = ['status' => 'error', 'message' => '❌ Invalid password.'];
                }
            } else {
                // No user found
                $response = ['status' => 'error', 'message' => '❌ No account found with that email or phone number.'];
            }
            $stmt->close();
        }
    } else {
        $response = ['status' => 'error', 'message' => '⚠️ Missing input values.'];
    }
} else {
    $response = ['status' => 'error', 'message' => 'Invalid request method.'];
}

$conn->close();

// --- Final Output ---
// Encode the final response array as JSON and send it.
// This is the ONLY echo in the entire script.
echo json_encode($response);

?>
