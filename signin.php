<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'final_project';
$home_url = 'home.html';
$signin = new Signin($host, $user, $password, $dbname);
$signin->handleRequest($home_url);
class Signin
{

    private $conn;

    public function __construct($host, $user, $password, $dbname)
    {
        $this->conn = new mysqli($host, $user, $password, $dbname);

        if ($this->conn->connect_error) {
            http_response_code(500);
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function handleRequest($home_url)
    {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['emailOrnum']) && isset($_POST['password'])) {

                $mail = trim($_POST['emailOrnum']);
                $pass = trim($_POST['password']);

                if (empty($mail) || empty($pass)) {
                    echo "⚠️ All fields are required.";
                    return;
                }

                // Hash the password using SHA-256
                $hashed = hash('sha256', $pass);

                // Prepared statement to prevent SQL injection
                $stmt = $this->conn->prepare("
                    SELECT email, phone_num, password_hash
                    FROM users
                    WHERE (email = ? OR phone_num = ?)
                ");
                $stmt->bind_param("ss", $mail, $mail);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $user = $result->fetch_assoc();

                    // Compare hashed password
                    if ($hashed === $user['password_hash']) {
                        // Set session after successful login
                        $_SESSION['user'] = $user['email']; // or use phone_num if preferred
                        header("Location: $home_url");
                        exit();
                    } else {
                        echo "❌ Invalid password.";
                    }
                } else {
                    echo "❌ No account found with that email or phone number.";
                }
 
                $stmt->close();
            } else {
                echo "⚠️ Missing input values.";
            }
        }
    }
}


?>