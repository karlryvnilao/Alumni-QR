<?php
include_once '../connection.php';

try {
    // Check if the form was submitted and the file was uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        
        // Get the image name and set the target directory
        $image = $_FILES['photo']['name'];
        $target = "../images/" . basename($image);
        
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            
            // Prepare the SQL statement to insert the image name into the database
            $sql = 'INSERT INTO `gallery` (`name`) VALUES (:name)';
            $stmt = $db->prepare($sql);
            
            // Bind the image name to the SQL statement
            $stmt->bindParam(':name', $image);
            
            // Execute the SQL statement
            $stmt->execute();
            
            // Redirect with a success message
            header("Location: ../../administrator/gallery.php?type=success&message=Photo added successfully.");
            exit();
        } else {
            // Handle file upload error
            throw new Exception('Failed to move the uploaded file.');
        }
    } else {
        // Handle upload error or missing file
        throw new Exception('No file uploaded or there was an upload error.');
    }
} catch (Exception $e) {
    // Handle any errors by redirecting with an error message
    header("Location: ../../administrator/gallery.php?type=error&message=" . $e->getMessage());
    exit();
}
