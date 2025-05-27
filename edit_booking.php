<?php
// Establish connection to the MySQL database
$db = new mysqli("localhost", "root", "", "car_rent");

// Check for connection error
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve booking ID from the form
    $id = $_POST['id'];

    // Retrieve the booking details from the database
    $query = "SELECT * FROM bookings WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Here, you can display a form with the current booking details
        // and allow the admin to modify them
        // For example:
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Edit Booking</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background-color: #f2f2f2;
                }
                form {
                    max-width: 600px;
                    margin: 0 auto;
                    background-color: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                }
                label {
                    display: block;
                    margin-bottom: 10px;
                    color: #333;
                }
                input[type="text"],
                input[type="date"] {
                    width: 100%;
                    padding: 10px;
                    margin-bottom: 15px;
                    border: 1px solid #ccc;
                    border-radius: 4px;
                    box-sizing: border-box;
                }
                input[type="submit"] {
                    background-color: #007bff;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 4px;
                    cursor: pointer;
                    width: 100%;
                }
                input[type="submit"]:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <h2>Edit Booking</h2>
            <form action="update_booking.php" method="post">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <label for="username">Username:</label>
                <input type="text" name="username" value="<?php echo $row['username']; ?>"><br>
                <label for="car_name">Car Name:</label>
                <input type="text" name="car_name" value="<?php echo $row['car_name']; ?>"><br>
                <label for="location">Location:</label>
                <input type="text" name="location" value="<?php echo $row['location']; ?>"><br>
                <label for="pickup_date">Pickup Date:</label>
                <input type="date" name="pickup_date" value="<?php echo $row['pickup_date']; ?>"><br>
                <label for="return_date">Return Date:</label>
                <input type="date" name="return_date" value="<?php echo $row['return_date']; ?>"><br>
                <input type="submit" value="Update">
            </form>
        </body>
        </html>
        <?php
    } else {
        echo "Booking not found.";
    }

    // Close the database connection
    $stmt->close();
    $db->close();
} else {
    // If the request method is not POST, redirect to the admin page
    header("Location: admin.php");
    exit();
}
?>
