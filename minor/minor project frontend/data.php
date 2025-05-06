 <?php
$name=$_POST['name'];
$phone=$_POST['phone'];
$gmail=$_POST['gmail'];
$pass=$_POST['password'];
$username=$_POST['username'];
$conn = mysqli_connect("localhost", "root","", "animoworld");
// ("localhost", "root", "", "animoworld");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
$sql = "INSERT INTO acctsofusers (gmail, password , name , username, phone) VALUES ('$gmail', '$pass','$name', '$username', '$phone' )";
if ($conn->query($sql) === TRUE) {
    echo "record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
?> 