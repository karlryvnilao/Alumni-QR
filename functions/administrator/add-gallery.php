<?php
include_once '../connection.php';
ob_start(); // Start output buffering

if (isset($_POST['student_id'])) {
    try {
        $student_id = filter_var($_POST['student_id'], FILTER_VALIDATE_INT);
        $achievement_id = !empty($_POST['achievement_id']) ? filter_var($_POST['achievement_id'], FILTER_VALIDATE_INT) : null;
        $motto = !empty($_POST['moto']) ? filter_var($_POST['moto'], FILTER_SANITIZE_STRING) : null; // Sanitize the motto input

        // Check if student ID is valid
        if ($student_id === false) {
            throw new Exception("Invalid Student ID.");
        }

        $stmt = $db->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$student_id]);
        $result = $stmt->fetch();

        if (!$result) {
            error_log("Invalid Student ID: " . $student_id);
            http_response_code(400);
            exit("Invalid Student ID.");
        }

        // Check if profile picture is uploaded and process it
        if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === 0) {
            $fileTmpPath = $_FILES['profile_pic']['tmp_name'];
            $fileName = basename($_FILES['profile_pic']['name']);
            $fileType = $_FILES['profile_pic']['type'];
            $fileSize = $_FILES['profile_pic']['size'];

            // Allowed file types and max size
            $allowedFileTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxFileSize = 2 * 1024 * 1024; // 2 MB

            if (in_array($fileType, $allowedFileTypes) && $fileSize <= $maxFileSize) {
                $newFileName = uniqid() . '_' . preg_replace('/[^a-zA-Z0-9-_\.]/', '_', $fileName);
                $uploadFileDir = '../../student/images/';
                $dest_path = $uploadFileDir . $newFileName;

                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    // Update profile picture, achievement ID, and motto
                    $query = "UPDATE students SET profile_pic = ?, achievement_id = ?, motto = ?, status = 'active' WHERE id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bindValue(1, $newFileName, PDO::PARAM_STR);
                    $stmt->bindValue(2, $achievement_id, PDO::PARAM_INT); // Bind achievement ID
                    $stmt->bindValue(3, $motto, PDO::PARAM_STR); // Bind motto
                    $stmt->bindValue(4, $student_id, PDO::PARAM_INT);

                    if ($stmt->execute()) {
                        header('Location: ../../administrator/gallery.php?type=success&message=Successfully Added');
                        exit;
                    } else {
                        echo "Error updating profile picture, achievement, and motto: " . implode(":", $stmt->errorInfo());
                    }
                } else {
                    echo "There was an error moving the uploaded file.";
                }
            } else {
                echo "Upload failed. Allowed file types: jpg, png, gif. Maximum size: 2MB.";
            }
        } else {
            echo "No file uploaded or there was an upload error: " . $_FILES['profile_pic']['error'];
        }
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
        echo "An error occurred. Please try again.";
    }
} else {
    http_response_code(400);
    echo "Invalid request method.";
}

// Close the database connection
if (isset($db)) {
    $db = null;
}
ob_end_flush(); // End output buffering
?>
