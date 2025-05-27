<?php

//start a session to manage user sesssion
session_start();

//establish a connection to the mysql database
$db=new mysqli("localhost","root","","car_rent");

//check for connection error
if ($db->connect_error){
    die("Connection failed: ".$db->connect_error);
    }

    //check of the request method is post (form submission)
   if($_SERVER["REQUEST_METHOD"]=="POST"){

    //Retrieve username and password from post data
    $username=$_POST['username'];
    $password=$_POST['password'];
   }

    //use a prepared  statement to prevent sql injection attacks.
    $query="SELECT * FROM users WHERE username=?";
    $stmt=$db->prepare($query);
    $stmt->bind_param("s",$username);
    $stmt->execute();
    $result=$stmt->get_result();

    //check if any rows are  returned(if the username exists)
if($result->num_rows>0){
    //fetch the data
    $row= $result->fetch_assoc();

    //use strcmp to compare the password entered with
    
    if(strcmp($password,$row['password'])===0){
        $_SESSION['username']=$username;
        // Add JavaScript to set localStorage values
        echo '<script>
            localStorage.setItem("isLoggedIn", "true");
            localStorage.setItem("username", "' . $username . '");
            window.location.href = "index.html";
        </script>';
        exit();
    }else{
        echo '<script>alert("Invalid  Password! Try Again");
        window.location.href="signin.html";</script>';
    }

    //close the prepared statement
    $stmt->close();
}
//close the database connection
$db->close();
   
?>