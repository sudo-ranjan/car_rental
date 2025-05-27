<?php
// Establish connection to the MySQL database
$db = new mysqli("localhost", "root", "", "car_rent");

// Check for connection error
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $id = $_POST['id'];
    $username = $_POST['username'];
    $car_name = $_POST['car_name'];
    $location = $_POST['location'];
    $pickup_date = $_POST['pickup_date'];
    $return_date = $_POST['return_date'];

    // Update the booking details in the database
    $query = "UPDATE bookings SET username=?, car_name=?, location=?, pickup_date=?, return_date=? WHERE id=?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssssi", $username, $car_name, $location, $pickup_date, $return_date, $id);
    $stmt->execute();

    // Redirect back to the admin page after updating
    header("Location: admin.php");
    exit();
} else {
    // If the request method is not POST, redirect to the admin page
    header("Location: admin.php");
    exit();
}
?>
