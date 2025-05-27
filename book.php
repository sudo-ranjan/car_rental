<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo '<script>
        alert("Please sign in to book a car.");
        window.location.href = "signin.html";
    </script>';
    exit();
}

// Include PHPMailer
require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Connect to MySQL
$db = new mysqli("localhost", "root", "", "car_rent");
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_SESSION['username'];
    $car_name = $_POST['car_name'];
    $location = $_POST['location'];
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    // Get user's email from DB
    $email_query = $db->prepare("SELECT email FROM users WHERE username = ?");
    $email_query->bind_param("s", $username);
    $email_query->execute();
    $email_result = $email_query->get_result();

    if ($email_result->num_rows === 0) {
        echo "<script>alert('User email not found.'); window.history.back();</script>";
        exit();
    }

    $email = $email_result->fetch_assoc()['email'];

    // Check car availability
    $check_query = "SELECT * FROM bookings 
                    WHERE car_name = ? 
                    AND (
                        (pickup_date BETWEEN ? AND ?) OR 
                        (return_date BETWEEN ? AND ?) OR
                        (? BETWEEN pickup_date AND return_date) OR 
                        (? BETWEEN pickup_date AND return_date)
                    )";
    $stmt = $db->prepare($check_query);
    $stmt->bind_param("sssssss", $car_name, $pickup_date, $return_date, $pickup_date, $return_date, $pickup_date, $return_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>
                alert('This car is already booked within the selected date range.');
                window.history.back();
              </script>";
        exit();
    }

    // Book the car
    $insert = $db->prepare("INSERT INTO bookings (username, car_name, location, pickup_date, return_date) 
                            VALUES (?, ?, ?, ?, ?)");
    $insert->bind_param("sssss", $username, $car_name, $location, $pickup_date, $return_date);
    $insert->execute();

    // Send confirmation email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'rockytomy20@gmail.com';      // 🔁 Replace with your Gmail
        $mail->Password   = 'gcur fxgf mzwg clad';         // 🔁 Replace with App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('rockytomy20@gmail.com', 'Car Rental Service'); // 🔁 Same as Username
        $mail->addAddress($email, $username);
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation';
        $mail->Body    = "Hi <b>$username</b>,<br><br>Your booking for <b>$car_name</b> from <b>$pickup_date</b> to <b>$return_date</b> at <b>$location</b> has been confirmed.<br><br>Thank you for choosing us!";

        $mail->send();

        echo "<script>
            alert('Booking confirmed! A confirmation email has been sent.');
            window.location.href = 'index.html';
        </script>";
        exit();
    } catch (Exception $e) {
        echo "<script>
            alert('Booking confirmed, but email could not be sent. Error: {$mail->ErrorInfo}');
            window.location.href = 'index.html';
        </script>";
        exit();
    }
}

$db->close();
?>
