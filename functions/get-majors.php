<?php
// Ensure that the database connection file is included
require_once 'conn.php'; // or the correct path to your database connection file

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    // Prepare the SQL query
    $query = "SELECT * FROM majors WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are majors for the selected course
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="majors[]" value="'.$row['id'].'" id="major_'.$row['id'].'">';
            echo '<label class="form-check-label" for="major_'.$row['id'].'">'.$row['major_name'].'</label>';
            echo '</div>';
        }
    } else {
        echo '<div>No majors available</div>';
    }
} else {
    echo 'No course ID received';
}
?>
