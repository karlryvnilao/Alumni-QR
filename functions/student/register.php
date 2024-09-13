<?php
include_once '../connection.php';
require_once 'phpqrcode/qrlib.php';

$path = 'qrcodes/';
$qrcode = $path . time() . ".png";
$qrimage = time() . ".png";

// Sanitize and hash input
$username = trim(filter_var($_POST['username'], FILTER_SANITIZE_SPECIAL_CHARS));
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$firstname = trim(filter_var($_POST['firstname'], FILTER_SANITIZE_SPECIAL_CHARS));
$lastname = trim(filter_var($_POST['lastname'], FILTER_SANITIZE_SPECIAL_CHARS));
$birthdate = $_POST['birthdate']; // Assuming valid date format is already ensured by the form
$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$course = intval($_POST['course']);
$civil = trim(filter_var($_POST['civil'], FILTER_SANITIZE_SPECIAL_CHARS));
$batch = intval($_POST['batch']);
$phone = trim(filter_var($_POST['phone'], FILTER_SANITIZE_SPECIAL_CHARS));
$status = 'pending';


$sql = "SELECT * FROM `users` WHERE `username` = :username";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    echo "Username '$username' already exists!";
    exit;
}

// Debugging: Check if we reach this point
echo "Inserting into users table...<br>";

// Insert into users table
$sql = "INSERT INTO `users` (`username`, `password`) VALUES (:username, :password)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);

// Check if the execution is successful
if ($stmt->execute()) {
    $user_id = $db->lastInsertId();
    echo "User inserted successfully with ID: $user_id <br>";
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Error inserting user: " . $errorInfo[2];
    exit;
}

// Insert into students table (the same logic applies for debugging here)
$sql = "INSERT INTO `students` (user_id, `firstname`, `lastname`, `birthdate`, `email`, `course`, `civil`, `batch`, `phone`, `qrimage`) 
        VALUES (:user_id, :firstname, :lastname, :birthdate, :email, :course, :civil, :batch, :phone, :qrimage)";
$stmt = $db->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->bindParam(':firstname', $firstname);
$stmt->bindParam(':lastname', $lastname);
$stmt->bindParam(':birthdate', $birthdate);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':course', $course);
$stmt->bindParam(':civil', $civil);
$stmt->bindParam(':batch', $batch);
$stmt->bindParam(':phone', $phone);
$stmt->bindParam(':qrimage', $qrimage);

if ($stmt->execute()) {
    QRcode::png($username, $qrcode, 'H', 4, 4);
    header('Location: ../../index.php?type=success&message=' . urlencode('Successfully Registered - Please wait for the approval of the administrator'));
} else {
    $errorInfo = $stmt->errorInfo();
    echo "Error inserting student: " . $errorInfo[2];
    exit;
}

// Close the connection
$db = null;
?>
