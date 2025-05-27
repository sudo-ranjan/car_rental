<?php
session_start();

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "car_rent";

// Get POST data
$adminName = $_POST['adminName'] ?? '';
$adminPassword = $_POST['adminPassword'] ?? '';

// Function to display error message in HTML format
function displayError($message) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Admin Sign In - Error</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: #f8f9fa;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
            }
            .error-box {
                background: white;
                padding: 30px 40px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0,0,0,0.1);
                max-width: 400px;
                text-align: center;
                color: #d9534f;
            }
            .error-box h2 {
                margin-bottom: 15px;
                font-weight: 600;
            }
            .error-box p {
                margin-bottom: 25px;
                font-size: 1.1rem;
                color: #555;
            }
            .error-box a {
                text-decoration: none;
                color: #007bff;
                font-weight: 600;
            }
            .error-box a:hover {
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="error-box">
            <h2>Sign In Error</h2>
            <p><?php echo htmlspecialchars($message); ?></p>
            <a href="admin_signin.html">Back to Sign In</a>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Check if fields are filled
if (empty($adminName) || empty($adminPassword)) {
    displayError("Please enter both username and password.");
}

// Create DB connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    displayError("Connection failed: " . $conn->connect_error);
}

// Prepare and execute query
$stmt = $conn->prepare("SELECT * FROM admin_credentials WHERE admin_name = ?");
$stmt->bind_param("s", $adminName);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    // Use password_verify if password is hashed
    if (password_verify($adminPassword, $row['password'])) {
        $_SESSION['admin_name'] = $adminName;
        $_SESSION['admin_logged_in'] = true;

        header("Location: admin.php");
        exit();
    } elseif ($adminPassword === $row['password']) {
        // Plain text fallback
        $_SESSION['admin_name'] = $adminName;
        $_SESSION['admin_logged_in'] = true;

        header("Location: admin.php");
        exit();
    } else {
        displayError("Invalid password.");
    }
} else {
    displayError("Admin not found.");
}

$stmt->close();
$conn->close();
?>
