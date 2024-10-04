<?php
require_once '../functions/connection.php';
include_once '../functions/get-batch.php';
include_once '../functions/student/get-students.php';
include_once '../functions/administrator/get-data-table.php';
if (session_start() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('location: ../index.php');
}
$achievements = getAchievements(); // Add this line to fetch achievements
?>
<!DOCTYPE html>
<html data-bs-theme="light" id="bg-animate" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Gallery - Alumni Management System for Yllana Bay View College</title>
    <meta name="twitter:image" content="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <meta name="description" content="Web-Based Alumni Management System for Yllana Bay View College">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/Hero-Clean-images.css">
    <link rel="stylesheet" href="../assets/css/Lightbox-Gallery-baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Basic-icons.css">
    <script>
function updateStudentInfo() {
    const studentSelect = document.getElementById('studentSelect');
    const selectedValue = studentSelect.value;
    document.getElementById('studentId').value = selectedValue; // Set the hidden input value

    const students = [
        <?php
        foreach ($students as $student) {
            echo "{
                id: {$student['id']},
                firstname: '{$student['firstname']}',
                lastname: '{$student['lastname']}',
                profile_pic: '{$student['profile_pic']}',
                batch: '{$student['batch']}',
                course: '{$student['course']}'
            },"; 
        }
        ?>
    ];

    const selectedStudent = students.find(student => student.id == selectedValue);
    const infoSection = document.getElementById('studentInfo');

    if (selectedStudent) {
        const profilePicSrc = `../student/images/${selectedStudent.profile_pic}`;
        infoSection.innerHTML = `
            <div class="card mt-3">
                <img src="${profilePicSrc}" id="studentImage" class="card-img-top" alt="${selectedStudent.firstname} ${selectedStudent.lastname}" style="width: 50%; height: auto; margin: auto;">
                <div class="card-body text-center">
                    <h5 class="card-title">${selectedStudent.firstname} ${selectedStudent.lastname}</h5>
                    <p class="card-text">Batch: ${selectedStudent.batch}</p>
                    <p class="card-text">Course: ${selectedStudent.course}</p>
                </div>
            </div>
        `;
        
        // Show the current profile picture
        document.getElementById('studentImage').src = profilePicSrc;
        document.getElementById('studentImage').style.display = 'block'; // Show image
    } else {
        infoSection.innerHTML = '';
        document.getElementById('studentImage').style.display = 'none'; // Hide image if no student is selected
    }
}


function previewProfilePic() {
    const profilePicInput = document.getElementById('profilePic');

    if (profilePicInput.files && profilePicInput.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const studentImage = document.getElementById('studentImage');
            studentImage.src = e.target.result; // Update the image with the new file
            studentImage.style.display = 'block'; // Show the uploaded image
        };
        reader.readAsDataURL(profilePicInput.files[0]);
    }
}
    </script>
</head>

<body id="page-top">
    <?php
    include_once '../functions/administrator/navbar.php';
    ?>
    <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
            <section>
                <div class="container-fluid">
                    <div class="d-sm-flex justify-content-between align-items-center mb-4">
                        <h3 class="text-dark mb-2">Gallery Management</h3>
                        <button class="btn btn-outline-primary mx-2 mb-2" type="button" data-bs-target="#add" data-bs-toggle="modal">Add Alumni</button>
                    </div>
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <p class="text-primary m-0 fw-bold"> List</p>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                                <table class="table my-0" id="dataTable">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Course</th>
                                            <th>Batch</th>
                                            <th>Status</th>
                                            <th class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php get_gallery(); ?>
                                    </tbody>
                                    <tfoot>
                                        <tr></tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<?php include_once '../functions/administrator/offcanva-menu.php'; ?>
<div class="modal fade" role="dialog" tabindex="-1" id="add">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="../assets/img/navbar.jpg" style="width: 10em;">
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <form action="../functions/administrator/add-gallery.php" method="post" enctype="multipart/form-data">
                <label for="studentSelect" class="form-label">Choose a student:</label>
                <select id="studentSelect" class="form-select" onchange="updateStudentInfo()">
                    <option value="">Select a student</option>
                    <?php
                    // Assuming $students is fetched from the database
                    if (!empty($students)) {
                        foreach ($students as $student) {
                            echo "<option value='{$student['id']}'>{$student['firstname']} {$student['lastname']}</option>";
                        }
                    } else {
                        echo "<option value=''>No students available</option>";
                    }
                    ?>
                </select>
                
                <input type="hidden" name="student_id" id="studentId" value="">

                <div id="studentInfo">
                    <!-- Student information will be displayed here -->
                </div>

                <label for="achievementSelect" class="form-label mt-3">Achievement:</label>
                <select id="achievementSelect" class="form-select" name="achievement_id">
                    <option value="">Select an Achievement (Optional)</option>
                    <?php
                    foreach ($achievements as $achievement) {
                        echo "<option value='{$achievement['id']}'>{$achievement['name']}</option>";
                    }
                    ?>
                </select>

                <label for="motto" class="form-label mt-3">Motto :</label>
                <input class="form-control form-control" type="text" name="moto" placeholder="Motto: "><label class="form-label" for="floatingInput">Batch Year :</label>

                <label for="profilePic" class="form-label mt-3">Change Profile Picture:</label>
                <input class="form-control mb-3" type="file" id="profilePic" name="profile_pic" accept="image/*" onchange="previewProfilePic()">
                <img id="studentImage" src="../assets/img/default_profile.png" alt="Profile Picture" class="img-fluid mt-2" style="width: 100px; height: auto; display: none;">

                <!-- Submit Button -->
                <button class="btn btn-primary w-100" type="submit">Upload</button>
            </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade" role="dialog" tabindex="-1" id="delete">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Delete</h4><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to remove this photo from the gallery?</p>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
                <form action="../functions/administrator/delete-gallery.php" method="post">
                    <input type="hidden" name="id" id="id">
                    <button class="btn btn-danger" type="submit">Delete</button></div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js"></script>
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="../assets/js/bs-init.js"></script>
    <script src="../assets/js/datatables.min.js"></script>
    <script src="../assets/js/three.min.js"></script>
    <script src="../assets/js/theme.js"></script>
    <script src="../assets/js/Lightbox-Gallery.js"></script>
    <script src="../assets/js/Lightbox-Gallery-baguetteBox.min.js"></script>
    <script src="../assets/js/sweetalert2.all.min.js"></script>
    <script src="../assets/js/main.js"></script>
    <script>
        $(document).ready(function() {
            $('#student-select').select2({
                placeholder: "Select a Student",
                allowClear: true
            });
        });
    </script>
</body>

</html>