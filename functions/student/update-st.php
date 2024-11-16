<?php
include_once '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Assuming input fields are validated properly
    $student_id = $_POST['student_id'];
    $firstname = htmlspecialchars(trim($_POST['firstname']));
    $lastname = htmlspecialchars(trim($_POST['lastname']));
    $birthdate = $_POST['birthdate']; // Make sure to validate date format
    $present_address = htmlspecialchars(trim($_POST['present_address']));
    $course = $_POST['course'];
    $civil = $_POST['civil'];
    $batch = $_POST['batch'];
    $achievement_id = $_POST['achievement_id'];
    $motto = htmlspecialchars(trim($_POST['motto']));

    // File upload handling
    $profile_pic = null; // Default to null if no file is uploaded
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
        $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
        $max_file_size = 5 * 1024 * 1024; // 5MB limit

        // Check file type and size
        if (in_array($file_ext, $allowed_ext) && $_FILES['profile_pic']['size'] <= $max_file_size) {
            // Process file upload
            $profile_pic = '../../student/images/' . basename($_FILES['profile_pic']['name']);
            if (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $profile_pic)) {
                // Handle error during file move
                die("Error uploading profile picture.");
            }
        } else {
            die("Invalid file type or file too large.");
        }
    }

    // Update student info in the database
    try {
        $sql = "UPDATE students SET firstname = ?, lastname = ?, birthdate = ?, present_address = ?, course = ?, civil = ?, batch = ?, achievement_id = ?, motto = ?, profile_pic = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$firstname, $lastname, $birthdate, $present_address, $course, $civil, $batch, $achievement_id, $motto, $profile_pic, $student_id]);

        // Redirect or show success message
        header("Location: success.php");
        exit;
    } catch (PDOException $e) {
        // Handle database errors
        die("Error: " . $e->getMessage());
    }
}
?>
