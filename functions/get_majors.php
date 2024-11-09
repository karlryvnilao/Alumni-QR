<?php
// test.php
require_once 'conn.php'; 

if (isset($_POST['course_id'])) {
    $course_id = $_POST['course_id'];

    $query = "SELECT * FROM majors WHERE course_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="form-check">';
            echo '<input class="form-check-input" type="checkbox" name="majors[]" value="' . $row['major_id'] . '" id="major_' . $row['major_id'] . '">';
            echo '<label class="form-check-label" for="major_' . $row['major_id'] . '">' . $row['major_name'] . '</label>';
            echo '</div>';
        }
    
    } else {
        echo 'No majors available for this course.';
    }
} else {
    echo 'No course ID received.';
}
?>
