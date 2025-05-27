<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page - Bookings</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        h2 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            margin-right: 5px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .edit-btn:hover, .delete-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h2>Admin Page - Bookings</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Car Name</th>
        <th>Location</th>
        <th>Pickup Date</th>
        <th>Return Date</th>
        <th>Actions</th>
    </tr>

    <?php
    // Establish connection to the MySQL database
    $db = new mysqli("localhost", "root", "", "car_rent");

    // Check for connection error
    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }

    // Retrieve all data from the bookings table
    $query = "SELECT * FROM bookings";
    $result = $db->query($query);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Display data in a table
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>".$row["id"]."</td>";
            echo "<td>".$row["username"]."</td>";
            echo "<td>".$row["car_name"]."</td>";
            echo "<td>".$row["location"]."</td>";
            echo "<td>".$row["pickup_date"]."</td>";
            echo "<td>".$row["return_date"]."</td>";
            echo '<td>
                    <form action="edit_booking.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="'.$row["id"].'">
                        <button type="submit" class="edit-btn">Edit</button>
                    </form>
                    <form action="delete_booking.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="'.$row["id"].'">
                        <button type="submit" class="delete-btn">Delete</button>
                    </form>
                  </td>';
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No bookings found.</td></tr>";
    }

    // Close the database connection
    $db->close();
    ?>
</table>

</body>
</html>
