<?php
include_once '../conn.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $firstname1 = $_POST['firstname'];
    $lastname1 = $_POST['lastname'];
    $birthdate1 = $_POST['birthdate'];
    $present_address1 = $_POST['present_address'];
    $course_id1 = $_POST['course'];
    $civil_status1 = $_POST['civil'];
    $batch_id1 = $_POST['batch'];
    $motto1 = $_POST['motto'];
    
    // Check if achievement_id is provided (optional)
    $achievement_id = isset($_POST['achievement_id']) && !empty($_POST['achievement_id']) ? $_POST['achievement_id'] : NULL;
    
    // Set the default status as 'active'
    $status = 'active';
    
    // Handle the profile picture upload
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $profile_pic = $_FILES['profile_pic'];
        $target_dir = "../../student/images/";
        $target_file = basename($profile_pic['name']); // Get only the file name
        move_uploaded_file($profile_pic['tmp_name'], $target_dir . $target_file);
    } else {
        // Use a default profile picture if none is uploaded
        $target_file = "default_profile.png";  // Save only the file name
    }

    // Insert the student data into the database
    $sql = "INSERT INTO students (firstname, lastname, birthdate, present_address, course, civil, batch, motto, profile_pic, achievement_id, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssis", $firstname1, $lastname1, $birthdate1, $present_address1, $course_id1, $civil_status1, $batch_id1, $motto1, $target_file, $achievement_id1, $status);
    
    if ($stmt->execute()) {
        // On success, redirect or show success message
        header('Location: ../../administrator/gallery.php?type=success&message=' . urlencode('Successfully Registered - Please check your email and wait for the administrator\'s approval.'));
    } else {
        // On failure, show error message
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
