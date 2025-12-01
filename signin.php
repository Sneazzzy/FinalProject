<?php
// Start the session to store user login status.
session_start();

// --- Database Connection Details ---
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'final_project';

// --- Page to redirect to on successful login ---
$home_url = 'home.html';

// --- Main Logic ---

// 1. Create a database connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check if the connection failed. If so, stop the script.
if ($conn->connect_error) {
    // Send a JSON error response
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . $conn->connect_error]);
    exit();
}

// 2. Check if the form was submitted using the POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 3. Check if the expected form fields are present
    if (isset($_POST['emailOrnum']) && isset($_POST['password'])) {

        // 4. Get and clean the user input
        $mail = trim($_POST['emailOrnum']);
        $pass = trim($_POST['password']);

        // 5. Validate the input (make sure fields are not empty)
        if (empty($mail) || empty($pass)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => '⚠️ All fields are required.']);
            exit();
        }

        // 5a. Validate the format of the email or phone number.
        // It must be a valid email OR consist only of digits.
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL) && !ctype_digit($mail)) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => '⚠️ Please enter a valid email or phone number format.']);
            exit();
        }

        // 6. Hash the submitted password using SHA-256 for comparison
        // This must match the hashing method used during signup.
        $hashed_password = hash('sha256', $pass);

        // 7. Prepare the SQL query to prevent SQL injection
        // The '?' are placeholders for the user's input.
        $sql = "SELECT email, phone_num, password_hash FROM users WHERE (email = ? OR phone_num = ?)";
        $stmt = $conn->prepare($sql);

        // 8. Bind the user's input to the placeholders
        // "ss" means both parameters are strings.
        $stmt->bind_param("ss", $mail, $mail);

        // 9. Execute the query
        $stmt->execute();
        $result = $stmt->get_result();

        // 10. Check if a user was found
        if ($result->num_rows > 0) {
            // Fetch the user's data from the database
            $user_data = $result->fetch_assoc();

            // 11. Compare the hashed password from the form with the one in the database
            if ($hashed_password === $user_data['password_hash']) {
                // Passwords match! Login is successful.

                // 12. Store user information in the session
                $_SESSION['user'] = $user_data['email'];

                // 13. Send a success response
                header('Content-Type: application/json');
                echo json_encode(['status' => 'success', 'redirect' => $home_url]);
                exit();
            } else {
                // Passwords do not match
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => '❌ Invalid password.']);
                exit();
            }
        } else {
            // No user found with the given email or phone number
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => '❌ No account found with that email or phone number.']);
            exit();
        }

        // 14. Close the statement
        $stmt->close();

    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => '⚠️ Missing input values.']);
        exit();
    }
}

// 15. Close the database connection
$conn->close();

?>
