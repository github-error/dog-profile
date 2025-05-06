<?php
$gmail=$_POST['email'];
$pass=$_POST['password'];
$name=$_POST['name'];
$username=$_POST['username'];
$phone=$_POST['phone'];
$conn = mysqli_connect("localhost", "root","", "animowrld");
// ("localhost", "root", "", "animoworld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "INSERT INTO accounts VALUES ('$gmail', '$pass','$name', '$username', '$phone' )";
if ($conn->query($sql) === TRUE) {
    echo "record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?> 



