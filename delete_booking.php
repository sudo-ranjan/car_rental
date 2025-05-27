<?php
// Establish connection to the MySQL database
$db = new mysqli("localhost", "root", "", "car_rent");

// Check for connection error
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the ID of the booking to be deleted
    $id = $_POST['id'];

    // Prepare a DELETE statement
    $query = "DELETE FROM bookings WHERE id = ?";

    // Prepare and bind the statement
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect the user back to the admin page after successful deletion
        header("Location: admin.php");
        exit();
    } else {
        // Handle any errors
        echo "Error: " . $db->error;
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$db->close();
?>
