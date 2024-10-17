<?php 
session_start();
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
    // Database connection
    $conn = mysqli_connect("localhost", "root", "", "lc");

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $student_id = $_SESSION['id'];
    
    // Query to get student and user details
    $stmt = $conn->prepare("
        SELECT s.*, u.username, s.batch
        FROM students s
        JOIN users u ON s.user_id = u.id
        WHERE s.user_id = ?
    ");
    $stmt->bind_param("i", $student_id); // Bind the integer parameter
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $student = $result->fetch_assoc();
        $fullName = $student['firstname'] . ' ' . $student['lastname'];
        $age =  date_diff(date_create($student['birthdate']), date_create('now'))->y;

        // Fetch batch information using the batch from the students table
        $batch = $student['batch'];
        $batchStmt = $conn->prepare("
        SELECT id, year
        FROM batch
        WHERE id = ?
        ");
        $batchStmt->bind_param("i", $batch); // Use the student's batch id
        $batchStmt->execute();
        $batchResult = $batchStmt->get_result();

        
        if ($batchResult && $batchResult->num_rows > 0) {
            $batch = $batchResult->fetch_assoc();
            $startYear = $batch['year'];
        } else {
            $startYear = 'Not Available';
        }

        $batchStmt->close();
        
        // Fetch all batches for display
        $allBatchesStmt = $conn->prepare("SELECT id, year FROM batch");
        $allBatchesStmt->execute();
        $allBatchesResult = $allBatchesStmt->get_result();
        
        $batches = [];
        if ($allBatchesResult && $allBatchesResult->num_rows > 0) {
            while ($row = $allBatchesResult->fetch_assoc()) {
                $batches[] = $row;
            }
        }

        $allBatchesStmt->close();
    } else {
        $student = null;
        $fullName = 'Not Available';
    }

    $stmt->close();
    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Alumni Management System</title>
    <meta name="twitter:image" content="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <meta name="description" content="Web-Based Alumni Management System">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/animate.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/Hero-Clean-images.css">
    <link rel="stylesheet" href="../assets/css/Lightbox-Gallery-baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Basic-icons.css">
</head>
<style>/* General styles for all screen sizes */
.card {
    width: 100%;
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Grid container for the profile section */
.grid-container {
    display: grid;
    grid-template-columns: 30% 40% 30%; /* Default for larger screens */
    gap: 10px;
}

.grid-item {
    padding: 20px;
    text-align: center; /* Center text by default */
}

.grid-item.text-left {
    text-align: left; /* Override text alignment for the second column */
}

/* Responsive 2-column layout for batches */
.two-column {
    display: grid;
    grid-template-columns: 1fr 1fr; /* Two equal columns */
    gap: 10px;
    position: relative;
}

.two-column::before {
    content: "";
    position: absolute;
    top: 0;
    left: 50%;
    width: 1px; /* Width of the dividing line */
    height: 100%;
    background-color: #000; /* Color of the dividing line */
    transform: translateX(-50%);
}

.two-column .grid-item h5,
.two-column .grid-item p {
    text-align: left;
}

/* Batch container for batches display */
.batch-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Responsive 3 columns */
    gap: 20px; /* Space between cards */
}

.batch-card {
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    position: relative;
}

/* Responsive styles */
@media (max-width: 1200px) {
    /* Adjust for smaller laptops */
    .grid-container {
        grid-template-columns: 25% 50% 25%;
    }
}

@media (max-width: 1024px) {
    /* Adjust for tablets */
    .grid-container {
        grid-template-columns: 1fr 1fr; /* 2 columns on tablets */
    }

    .batch-container {
        grid-template-columns: 1fr 1fr; /* 2 columns for batches */
    }
}

@media (max-width: 768px) {
    /* Adjust for smaller tablets and large phones */
    .grid-container {
        grid-template-columns: 1fr; /* Full-width columns on smaller screens */
    }

    .card {
        width: 90%;
        padding: 15px;
    }

    .batch-container {
        grid-template-columns: 1fr; /* Full-width cards */
    }
}

@media (max-width: 576px) {
    /* Adjust for mobile devices */
    .grid-container {
        grid-template-columns: 1fr; /* Single column on mobile */
    }

    .card {
        width: 100%;
        padding: 10px;
    }

    .batch-container {
        grid-template-columns: 1fr; /* Single column */
    }

    .batch-card {
        padding: 15px;
    }

    .two-column {
        grid-template-columns: 1fr; /* Single column for two-column layout */
        gap: 5px;
    }

    .two-column::before {
        display: none; /* Hide the dividing line */
    }
}
body {
    background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('../assets/img/bg.jpg');
    background-size: cover; /* Cover the entire viewport */
    background-position: center; /* Center the image */
    background-repeat: no-repeat; /* Prevent the image from repeating */
    margin: 0; /* Remove default margin */
    height: 100vh; /* Full height for the body */
    display: flex; /* Use flexbox for centering */
    justify-content: center; /* Center horizontally */
    align-items: center; /* Center vertically */
    position: relative; /* Relative positioning for the overlay */
    font-family: 'Roboto', sans-serif; /* Use Roboto as the main font */
}

/* Dark overlay */
.overlay {
    position: absolute; /* Absolute positioning to cover the entire body */
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background-color: rgba(0, 0, 0, 0.5); /* Black with 50% opacity */
    z-index: 1; /* Place overlay behind the content */
}
nav.navbar.navbar-expand-md.shadow {
    background-color: white;
}
</style>
<body>
    <div class="overlay">
<div class="d-flex flex-column" id="content-wrapper">
<?php include_once '../functions/student/navbar-menu.php'; ?>
    <div class="card">
        <div class="card-header">
            <div class="card-title">Student Profile</div>
            <!-- <div class="card-tools">
                <button class="btn btn-info btn-border btn-round btn-sm" onclick="printDiv('printThis')">
                    <i class="fa fa-print"></i>
                    Print Report
                </button>
            </div> -->
        </div>
        <div class="card-body m-5" id="printThis">
            <div class="grid-container">
                <div class="grid-item">
                    <img src="<?= preg_match('/data:image/i', $student['profile_pic']) ? $student['profile_pic'] : '../student/images/'.$student['profile_pic'] ?>" alt="Student Profile" class="img-fluid">
                </div>
                <div class="grid-item text-left">
                    <p>Student Number - <?= $student['username'] ?></p>
                    <p>Full Name - <?= $fullName ?></p>
                    <p>Age - <?php echo $age ?></p>
                    <p>Batch - <?= $startYear ?></p>
                    <p>Birthdate - <?= date('F d, Y', strtotime($student['birthdate'])) ?></p>
                    <p>Address - <?= ucwords(trim($student['present_address'])) ?></p>
                    <p>Contact - <?= $student['phone'] ?></p>
                    <?php if (!empty($batch)) : ?>
                    <p><strong></strong> <?= $batch['year'] ?></p>
                    <a href="yearbook.php?batch=<?= $batch['id'] ?>" class="view-yearbook-btn mt-3">View Yearbook</a>
            <?php else : ?>
                <p>No batch information is available at the moment.</p>
            <?php endif; ?>
                </div>
                <div class="grid-item">
                <img src="<?= preg_match('/data:image/i', $student['qrimage']) ? $student['qrimage'] : '../functions/student/qrcodes/'.$student['qrimage'] ?>" alt="Student Profile" class="img-fluid">
                </div>
        </div>
            </div>
    </div>

    <!-- <div class="card">
    <div class="card-header">
        <div class="card-title">Batch Information</div>
    </div>
    <div class="card-body">
        <div class="batch-container">
            <?php if (!empty($batch)) : ?>
                <div class="batch-card">
                    <p><strong></strong> <?= $batch['year'] ?></p>
                    <a href="yearbook.php?batch=<?= $batch['id'] ?>" class="view-yearbook-btn mt-3">View Yearbook</a>
                </div>
            <?php else : ?>
                <p>No batch information is available at the moment.</p>
            <?php endif; ?>
        </div>
        </div>
    </div> -->


        <div class="modal fade" role="dialog" tabindex="-1" id="sign-out">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Sign out</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to sign out?</p>
                    </div>
                    <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                    <a class="btn btn-danger" type="button" href="../functions/logout.php">Sign out</a></div>
                </div>
            </div>
        </div>
                </div>
                </div>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="../assets/js/bs-init.js"></script>
        <script src="../assets/js/datatables.min.js"></script>
        <script src="../assets/js/three.min.js"></script>
        <script src="../assets/js/theme.js"></script>
        <script src="../assets/js/Lightbox-Gallery.js"></script>
        <script src="../assets/js/Lightbox-Gallery-baguetteBox.min.js"></script>
        <script src="../assets/js/sweetalert2.all.min.js"></script>
        <script src="../assets/js/vanta.fog.min.js"></script>
        <script src="../assets/js/main.js"></script>		
    <script>
         function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = '<html><head><title>Print</title></head><body>' + printContents + '</body></html>';

        window.print();

        document.body.innerHTML = originalContents;
    }
    document.addEventListener("DOMContentLoaded", function() {
    var modal = document.getElementById("pensionStatusModal");
    var btn = document.querySelector(".btn-secondary"); // Button that opens the modal
    var span = document.getElementsByClassName("close-btn")[0];
    
    // Open the modal
    btn.onclick = function() {
        modal.style.display = "block";
    }

    // Close the modal
    span.onclick = function() {
        modal.style.display = "none";
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    }
});

// Update pension status
function updatePensionStatus() {
    var status = document.getElementById("pensionStatus").value;
    var studentId = document.getElementById("studentId").value;
    var statusMessage = document.getElementById("statusMessage");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_pension_status.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                statusMessage.innerHTML = "<p>Status updated successfully.</p>";
            } else {
                statusMessage.innerHTML = "<p>Failed to update status. Please try again.</p>";
            }
        }
    };
    xhr.send("pensionStatus=" + status + "&studentId=" + studentId);
}


    </script>
</body>
</html>
<?php 
}else{
     header("Location: index.php");
     exit();
}
 ?>