<?php
// Load PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Connect to MySQL database
$db = new mysqli("localhost", "root", "", "car_rent");

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Get form data
$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];

// Check if username or email already exists
$check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
$result = $db->query($check_query);

if ($result->num_rows > 0) {
    echo '<script>alert("Username or email already exists. Please choose another."); window.location.href="signup.html";</script>';
    exit();
}

// Insert user into database
$insert_query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
if ($db->query($insert_query) === TRUE) {

    // Initialize PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;

        // 🟢 Replace this with your Gmail and app password
        $mail->Username   = 'rockytomy20@gmail.com';           // Your Gmail address
        $mail->Password   = 'gcur fxgf mzwg clad';              // Gmail App Password

        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Sender and recipient
        $mail->setFrom('rockytomy20@gmail.com', 'Car Rental Service');
        $mail->addAddress($email, $username); // Send to user's email

        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Registration Successful';
        $mail->Body    = "
            <h2>Hello, $username!</h2>
            <p>Thank you for registering with our Car Rental Service.</p>
            <p>Your registration was successful!</p>
        ";

        // Send the email
        $mail->send();

        echo '<script>
            localStorage.setItem("isLoggedIn", "true");
            localStorage.setItem("username", "' . $username . '");
            alert("Registration successful! Confirmation email sent.");
            window.location.href = "index.html";
        </script>';

    } catch (Exception $e) {
        echo '<script>alert("Registered, but email could not be sent. Mailer Error: ' . $mail->ErrorInfo . '"); window.location.href="signin.html";</script>';
    }

} else {
    echo "Error: " . $insert_query . "<br>" . $db->error;
}

// Close the connection
$db->close();
?>
