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

// Get batch ID from query parameter
$batch_id = isset($_GET['batch']) ? intval($_GET['batch']) : 0;

// Query to get batch information
$batchStmt = $conn->prepare("
    SELECT year
    FROM batch
    WHERE id = ?
");
if ($batchStmt === false) {
    die("Prepare failed: " . $conn->error);
}
$batchStmt->bind_param("i", $batch_id);
$batchStmt->execute();
$batchResult = $batchStmt->get_result();

if ($batchResult && $batchResult->num_rows > 0) {
    $batch = $batchResult->fetch_assoc();
    $startYear = $batch['year'];
} else {
    $startYear = 'Not Available';
}

// Query to get students in the selected batch who are approved, including course names
$studentsStmt = $conn->prepare("
    SELECT s.*, u.username, c.name AS course_name
    FROM students s
    JOIN users u ON s.user_id = u.id
    JOIN courses c ON s.course = c.id
    WHERE s.batch = ? AND u.status = 'approved'
");
if ($studentsStmt === false) {
    die("Prepare failed: " . $conn->error);
}
$studentsStmt->bind_param("i", $batch_id);
$studentsStmt->execute();
$studentsResult = $studentsStmt->get_result();

$students = [];
if ($studentsResult && $studentsResult->num_rows > 0) {
    while ($row = $studentsResult->fetch_assoc()) {
        $students[] = $row;
    }
}

$studentsStmt->close();
$batchStmt->close();
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yearbook - <?= htmlspecialchars($startYear) ?></title>
    <style>
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            width: 100%;
            max-width: 300px;
            margin: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .card-img {
            width: 200px;
            height: 200px;
            background-color: #f9f9f9;
            background-size: cover;
            background-position: center;
            border-radius: 50%;
        }
        .card-content {
            padding: 15px;
            text-align: center;
        }
        .card-content h3 {
            margin: 10px 0;
            font-size: 1.2em;
        }
        .card-content p {
            margin: 5px 0;
            color: #555;
        }
        .view-yearbook-btn, .print-btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            margin-top: 10px;
        }
        .view-yearbook-btn:hover, .print-btn:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .container {
                width: 95%;
            }
            .card {
                max-width: 100%;
                margin: 10px 0;
            }
        }
        .grid-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr); /* 4 cards per row */
            gap: 20px; /* Space between cards */
        }
        @media (max-width: 1200px) {
            .grid-container {
                grid-template-columns: repeat(3, 1fr); /* 3 cards per row for medium screens */
            }
        }
        @media (max-width: 768px) {
            .grid-container {
                grid-template-columns: repeat(2, 1fr); /* 2 cards per row for small screens */
            }
        }
        @media (max-width: 480px) {
            .grid-container {
                grid-template-columns: 1fr; /* 1 card per row for extra small screens */
            }
        }
        .card-header {
            text-align: center;
        }

        /* Print styles */
        @media print {
            body * {
                visibility: hidden;
            }
            .container, .container * {
                visibility: visible;
            }
            .container {
                position: absolute;
                left: 0;
                top: 0;
            }
            .print-btn, .view-yearbook-btn {
                display: none;
            }
        }
    </style>
    <script>
        function printYearbook() {
            window.print();
        }
    </script>
</head>
<body>
<div class="container">
    <div class="card-header">
        <h1>Yearbook for Batch (<?= htmlspecialchars($startYear) ?>)</h1>
        <!-- Print button -->
        <button class="print-btn" onclick="printYearbook()">Print Yearbook</button>
    </div>
    <div class="grid-container">
        <?php foreach ($students as $student) : ?>
            <div class="card">
                <div class="card-img" style="background-image: url('<?= preg_match('/data:image/i', $student['profile_pic']) ? $student['profile_pic'] : '../student/images/'.$student['profile_pic'] ?>');"></div>
                <div class="card-content">
                    <h3><?= htmlspecialchars($student['firstname'] . ' ' . $student['lastname']) ?></h3>
                    <p><?= htmlspecialchars($student['username']) ?></p>
                    <p><?= htmlspecialchars($startYear) ?></p>
                    <p><?= htmlspecialchars($student['course_name']) ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
</body>
</html>
