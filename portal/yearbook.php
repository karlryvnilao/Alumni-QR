<?php
session_start();

if (!isset($_SESSION['id']) || !isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = mysqli_connect("localhost", "root", "", "ybvc");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get batch ID and course from query parameter
$batch_id = isset($_GET['batch']) ? intval($_GET['batch']) : 0;
$selectedCourse = isset($_GET['course']) ? $_GET['course'] : 'all'; // Default to 'all' if no course is selected

// Query to get batch information
$batchStmt = $conn->prepare("SELECT year FROM batch WHERE id = ?");
$batchStmt->bind_param("i", $batch_id);
$batchStmt->execute();
$batchResult = $batchStmt->get_result();

if ($batchResult && $batchResult->num_rows > 0) {
    $batch = $batchResult->fetch_assoc();
    $startYear = $batch['year'];
} else {
    $startYear = 'Not Available';
}

// Modify SQL query to filter students by selected course
$studentsQuery = "
    SELECT s.*, u.username, c.name AS course_name, m.major_name AS major_name
    FROM students s
    JOIN users u ON s.user_id = u.id
    JOIN courses c ON s.course = c.id
    LEFT JOIN majors m ON s.major_id = m.id
    WHERE s.batch = ? AND u.status = 'approved'
";
if ($selectedCourse !== 'all') {
    // Only show students from the selected course
    $studentsQuery .= " AND c.name = ?";
}
$studentsQuery .= " ORDER BY c.name, s.lastname ASC";

$studentsStmt = $conn->prepare($studentsQuery);
if ($selectedCourse === 'all') {
    $studentsStmt->bind_param("i", $batch_id);
} else {
    $studentsStmt->bind_param("is", $batch_id, $selectedCourse);
}
$studentsStmt->execute();
$studentsResult = $studentsStmt->get_result();

$studentsByCourse = []; // Initialize the array
if ($studentsResult && $studentsResult->num_rows > 0) {
    while ($row = $studentsResult->fetch_assoc()) {
        // Group students by course name
        $studentsByCourse[$row['course_name']][] = $row;
    }
}

$studentsStmt->close();
$batchStmt->close();

// Query to get all available courses for the dropdown
$coursesStmt = $conn->prepare("SELECT id, name FROM courses");
$coursesStmt->execute();
$coursesResult = $coursesStmt->get_result();

$coursesList = [];
if ($coursesResult && $coursesResult->num_rows > 0) {
    while ($course = $coursesResult->fetch_assoc()) {
        $coursesList[] = $course;
    }
}
$coursesStmt->close();

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <title>Yearbook - <?= htmlspecialchars($startYear) ?></title>
    <style>
        /* General Styles */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f4f9;
            color: #333;
        }

        h1, h2 {
            font-family: 'Poppins', sans-serif;
            color: #2c3e50;
        }

        .wrapper {
            padding: 40px 20px;
        }

        .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-body {
            padding: 1.5rem;
            text-align: center;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            margin-bottom: 0.75rem;
            color: #34495e;
        }

        .card-text {
            color: #7f8c8d;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .search-bar {
            margin-bottom: 30px;
        }
    </style>
    <script>
        // Search and filter students by course
        function filterStudents() {
            const searchQuery = document.getElementById('student-search').value.toLowerCase();
            const selectedCourse = document.getElementById('course-filter').value.toLowerCase();
            const courseSections = document.querySelectorAll('.course-section');

            courseSections.forEach(section => {
                const courseName = section.querySelector('h2').textContent.toLowerCase();
                const students = section.querySelectorAll('.card');
                let hasMatchingStudent = false;

                students.forEach(student => {
                    const name = student.querySelector('.card-title').textContent.toLowerCase();
                    const matchesSearch = name.includes(searchQuery);
                    const matchesCourse = selectedCourse === 'all' || courseName === selectedCourse;

                    if (matchesSearch && matchesCourse) {
                        student.parentElement.style.display = ''; // Show student
                        hasMatchingStudent = true;
                    } else {
                        student.parentElement.style.display = 'none'; // Hide student
                    }
                });

                // Hide the entire course section if no student matches
                if (hasMatchingStudent) {
                    section.style.display = ''; // Show course section
                } else {
                    section.style.display = 'none'; // Hide course section
                }
            });
        }

        // Reload the page when a course is selected to apply filtering
        function handleCourseChange() {
            const selectedCourse = document.getElementById('course-filter').value;
            const batchId = <?= $batch_id ?>;
            window.location.href = `?batch=${batchId}&course=${selectedCourse}`;
        }
    </script>
</head>
<body>
<div class="wrapper">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <button onclick="window.location.href='home.php'" class="btn btn-link text-primary">
                <i class="fas fa-arrow-left" style="font-size: 1.5rem;"></i>
            </button>
            <h1>Yearbook for Batch (<?= htmlspecialchars($startYear) ?>)</h1>
            <div class="mt-4 text-center">
                <a href="print_all.php?batch=<?= $batch_id ?>" class="btn btn-secondary">Print All Data</a>
            </div>
        </div>

        <!-- Search and Dropdown -->
        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" id="student-search" class="form-control" placeholder="Search for a student..." oninput="filterStudents()">
            </div>
            <div class="col-md-6">
                <select id="course-filter" class="form-select" onchange="handleCourseChange()">
                    <option value="all" <?= $selectedCourse === 'all' ? 'selected' : '' ?>>All Courses</option>
                    <?php foreach ($coursesList as $course): ?>
                        <option value="<?= htmlspecialchars($course['name']) ?>" <?= $selectedCourse === $course['name'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($course['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <!-- Course Sections -->
        <?php foreach ($studentsByCourse as $courseName => $students): ?>
            <div class="course-section">
                <h2 class="mt-4"><?= htmlspecialchars($courseName) ?></h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-5 g-4">
                    <?php foreach ($students as $student): ?>
                        <div class="col">
                            <div class="card h-100">
                            <div
        class="card-img-top"
        style="
          background-image: url('<?= !empty($student['profile_pic']) ? (preg_match('/data:image/i', $student['profile_pic']) ? $student['profile_pic'] : '../student/images/'.$student['profile_pic']) : 'default-avatar.jpg' ?>');
          background-size: cover;
          background-position: center;
          height: 250px;
        "
      ></div>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($student['lastname']) ?>, <?= htmlspecialchars($student['firstname']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($student['username']) ?></p>
                                    <p class="card-text"><?= htmlspecialchars($student['major_name']) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>

