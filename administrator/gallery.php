<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../functions/connection.php';
include_once '../functions/get-batch.php';
include_once '../functions/student/get-students.php';
include_once '../functions/administrator/get-data-table.php';
// include_once '../functions/get-gallery.php';
include_once '../functions/get-data.php';
if (session_start() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('location: ../index.php');
}
$achievements = getAchievements(); // Add this line to fetch achievements

// $studentId = $_GET['id'] ?? 0; // Example student ID
// $query = "SELECT * FROM students WHERE id = ?";
// $stmt = $pdo->prepare($query);
// $stmt->execute([$studentId]);
// $student = $stmt->fetch(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
<html data-bs-theme="light" id="bg-animate" lang="en">

<head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Course - Alumni Management System for Yllana Bay View College</title>
    <meta name="twitter:image" content="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <meta name="description" content="Web-Based Alumni Management System for Yllana Bay View College">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="icon" type="image/webp" sizes="450x450" href="https://student.lemerycolleges.edu.ph/images/favicon.png">
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/Hero-Clean-images.css">
    <link rel="stylesheet" href="../assets/css/Lightbox-Gallery-baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Basic-icons.css">
    
</head>
<style>
div#selectedStudentInfo {
    align-items: center;
    text-align: center;
}
</style>
<body id="page-top">
    <?php
    include_once '../functions/administrator/navbar.php';
    ?>
    <div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <section>
            <div class="container-fluid">
            <div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <section>
            <div class="container-fluid">
                <div class="d-sm-flex justify-content-between align-items-center mb-4">
                    <h3 class="text-dark mb-2">Gallery Management</h3>
                    <!-- Modal trigger button -->
                    <button class="btn btn-outline-primary mx-2 mb-2" type="button" data-bs-target="#add" data-bs-toggle="modal">Add Alumni</button>
                </div>

                <!-- Card containing the table -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <div class="row">
                            <div class="col-md-6">
                            <p class="text-primary m-0 fw-bold">List</p>
                            </div>
                            <div class="col-md-3">
                            <input type="text" id="searchInput" class="form-control mx-2 mb-2" placeholder="Search students...">
                            </div>
                        </div>
                        
                    </div>
                    <div class="card-body">
                        <div class="table-responsive table mt-2" id="dataTable-1" role="grid" aria-describedby="dataTable_info">
                            <table class="table my-0" id="dataTable">
                                <thead>
                                    <tr>
                                        <th onclick="sortTable(0)">Name <i class="fa fa-sort"></i></th>
                                        <th onclick="sortTable(1)">Course <i class="fa fa-sort"></i></th>
                                        <th onclick="sortTable(2)">Batch <i class="fa fa-sort"></i></th>
                                        <th onclick="sortTable(3)">Status <i class="fa fa-sort"></i></th>
                                        <th class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php get_gallery(); // Function to populate the table ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Name</th>
                                        <th>Course</th>
                                        <th>Batch</th>
                                        <th>Status</th>
                                        <th class="text-center">Action</th>
                                    </tr>
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
                <div class="modal-header"><img src="../assets/img/navbar.jpg" style="width: 10em;"><button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                <div id="success-alert" class="alert alert-success alert-dismissible fade show d-none" role="alert">
                    <span id="success-message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                
                <!-- Error Notification -->
                <div id="error-alert" class="alert alert-danger alert-dismissible fade show d-none" role="alert">
                    <span id="error-message"></span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <form class="needs-validation" action="../functions/student/reg.php" method="post" enctype="multipart/form-data" novalidate>
                <div class="d-flex flex-column align-items-center mb-4"></div>
                
                <!-- Selected Student Info -->
                <div id="selectedStudentInfo" class="mb-3">
                    <img id="studentImage" src="../assets/img/default_profile.png" alt="Profile Picture" class="img-fluid" style="width: 100px; height: auto;">
                    <h5 id="studentName">Select a student</h5>
                    <p id="studentMotto">Motto will be displayed here.</p>
                </div>

                <!-- Hidden Field to Store Selected Student ID -->
                <input type="hidden" name="student_id" id="studentId" value="">

                <!-- Achievement Dropdown -->
                <div class="mb-3">
                    
                    <label for="achievementSelect" class="form-label">Achievement:</label>
                    
                    <select id="achievementSelect" class="form-select" name="achievement_id">
                        <option value="">Select an Achievement (Optional)</option>
                        <?php foreach ($achievements as $achievement) : ?>
                            <option value="<?php echo $achievement['id']; ?>"><?php echo $achievement['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="motto" class="form-label mt-3">Motto:</label>
                    <input class="form-control" type="text" name="motto" id="motto1" placeholder="Enter Motto">
                </div>
                <!-- Profile Picture Upload -->
                <div class="mb-3">
                    <label for="profilePic" class="form-label">Change Profile Picture:</label>
                    <input class="form-control" type="file" id="profilePic1" name="profile_pic" accept="image/*" onchange="previewProfilePic()">
                </div>

                <!-- Submit Button -->
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="firstname1" type="text" name="firstname" placeholder="Firstname" required>
                                <label class="form-label" for="firstname">Firstname:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="lastname1" type="text" name="lastname" placeholder="Lastname" required>
                                <label class="form-label" for="lastname">Lastname:</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" name="birthdate" type="date" required>
                                <label class="form-label">Birthdate:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="present_address1" type="text" name="present_address" placeholder="Address" required>
                                <label class="form-label">Address:</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <select class="form-select" required name="course" id="course">
                                  <optgroup label="Course">
                                      <?php get_courses(); ?>
                                  </optgroup>
                              </select>
                            <label class="form-label">Course:</label>
                        </div>
                      </div>
                      <div class="col">
                      <label class="form-label">Majors:</label>
                        <div id="majors-container">
                              <!-- Checkboxes for majors will be populated dynamically -->
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" required name="civil">
                                    <optgroup label="Status">
                                        <option value="Single" selected="">Single</option>
                                        <option value="Married">Married</option>
                                        <option value="Widow">Widow</option>
                                    </optgroup>
                                </select>
                                <label class="form-label">Civil Status:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select" required name="batch">
                                    <optgroup label="Batch">
                                        <?php get_batches(); ?>
                                    </optgroup>
                                </select>
                                <label class="form-label">Batch:</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary w-100 mb-3" type="submit">Sign up</button>
                
                </form>
                </div>
                <div class="modal-footer"><button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button></div>
            </div>
        </div>
    </div>

    <!--update-->
    <div class="modal fade" role="dialog" tabindex="-1" id="addStudentModal">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <img src="../assets/img/navbar.jpg" style="width: 10em;">
                <button class="btn-close" type="button" aria-label="Close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="../functions/student/update-st.php" method="post" enctype="multipart/form-data" class="p-3">
                <input type="hidden" name="student_id" id="student_id">
                <!-- <input type="hidden" name="student_id" id="studentId" value="<?php echo $student['id'] ?? ''; ?>"> -->
                    <!-- Selected Student Info -->
                    <div id="selectedStudentInfo" class="mb-3">
                        <img id="studentImage" src="../assets/img/default_profile.png" alt="Profile Picture" class="img-fluid" style="width: 100px; height: auto;">
                        <h5 id="studentName">Select a student</h5>
                        <p id="studentMotto">Motto will be displayed here.</p>
                    </div>


                    <!-- Achievement Dropdown -->
                    <div class="mb-3">
                        <label for="achievementSelect" class="form-label">Achievement:</label>
                        <select id="achievementSelect" class="form-select" name="achievement_id">
                            <option value="">Select an Achievement (Optional)</option>
                            <?php if (!empty($achievements)) : ?>
                                <?php foreach ($achievements as $achievement) : ?>
                                    <option value="<?php echo $achievement['id']; ?>" <?php echo ($achievement['id'] == ($student['achievement_id'] ?? '')) ? 'selected' : ''; ?>>
                                        <?php echo $achievement['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <option value="">No achievements available</option>
                            <?php endif; ?>

                        </select>
                    </div>

                    <!-- Motto -->
                    <div class="mb-3">
                        <label for="motto" class="form-label mt-3">Motto:</label>
                        <input class="form-control" type="text" name="motto" id="motto" value="<?php echo $student['motto'] ?? ''; ?>" placeholder="Enter Motto">
                    </div>

                    <!-- Profile Picture Upload -->
                    <div class="mb-3">
                        <label for="profilePic" class="form-label">Change Profile Picture:</label>
                        <input class="form-control" type="file" id="profilePic" name="profile_pic" accept="image/*" onchange="previewProfilePic()">
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                            <input type="text" name="firstname" id="firstname" class="form-control" value="<?= htmlspecialchars($student_data['firstname'] ?? '') ?>" required>
                                <label class="form-label" for="firstname">Firstname:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="lastname" type="text" name="lastname" 
                                value="<?php echo $student['lastname'] ?? ''; ?>" >
                                <label class="form-label" for="lastname">Lastname:</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <input class="form-control" id="birthdate" type="date" name="birthdate" 
                                value="<?php echo $student['birthdate'] ?? ''; ?>" >
                                <label class="form-label">Birthdate:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                            <input class="form-control" id="present_address" type="text" name="present_address" 
                            value="<?php echo $student['present_address'] ?? ''; ?>" >
                                <label class="form-label">Address:</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select"  name="course" id="course">
                                    <optgroup label="Course">
                                        <?php get_courses(); ?>
                                    </optgroup>
                                </select>
                                <label class="form-label">Course:</label>
                            </div>
                        </div>
                        <div class="col">
                            <label class="form-label">Majors:</label>
                            <div id="majors-container">
                                <!-- Checkboxes for majors should be dynamically populated here based on selected course -->
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-floating mb-3">
                            <select class="form-select"  name="civil">
                                <optgroup label="Status">
                                    <option value="Single" <?php echo (($student['civil'] ?? '') === 'Single') ? 'selected' : ''; ?>>Single</option>
                                    <option value="Married" <?php echo (($student['civil'] ?? '') === 'Married') ? 'selected' : ''; ?>>Married</option>
                                    <option value="Widow" <?php echo (($student['civil'] ?? '') === 'Widow') ? 'selected' : ''; ?>>Widow</option>
                                </optgroup>
                            </select>

                                <label class="form-label">Civil Status:</label>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-floating mb-3">
                                <select class="form-select"  name="batch">
                                    <optgroup label="Batch">
                                        <?php get_batches(); ?>
                                    </optgroup>
                                </select>
                                <label class="form-label">Batch:</label>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 mb-3" type="submit">Update</button>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn btn-light" type="button" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<!-- Delete Modal -->
<div class="modal fade" id="delete" tabindex="-1" aria-labelledby="deleteLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this student?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
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
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/vanta.fog.min.js"></script>
    <script>


function selectStudent(student) {
    console.log("Selected student:", student);  // Log the entire student object
    document.getElementById('student_id').value = student.id || '';
    document.getElementById('firstname').value = student.firstname || '';
    document.getElementById('lastname').value = student.lastname || '';  // Check if lastname is empty
    document.getElementById('birthdate').value = student.birthdate || '';
    document.getElementById('present_address').value = student.present_address || '';
    document.getElementById('studentImage').src = student.profile_pic 
        ? `../assets/img/${student.profile_pic}` 
        : '../assets/img/default_profile.png';
    document.getElementById('studentName').innerText = `${student.firstname || ''} ${student.lastname || 'Unknown Last Name'}`;
    document.getElementById('studentMotto').innerText = student.motto || 'Motto will be displayed here.';
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


 //majors
 document.getElementById('course').addEventListener('change', function() {
    var courseId = this.value;
    console.log("Course ID selected: " + courseId);  // Debugging line

    var majorsContainer = document.getElementById('majors-container');
    majorsContainer.innerHTML = '';  // Clear majors container

    if (courseId !== "") {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "../functions/get-majors.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                console.log("Response from server: " + xhr.responseText);  // Debugging line
                majorsContainer.innerHTML = xhr.responseText;
            }
        };
        xhr.send("course_id=" + courseId);
    }
});




// Filter function for each column
function filterTable(inputId, columnIndex) {
    const input = document.getElementById(inputId);
    const filter = input.value.toUpperCase();
    const table = document.getElementById("dataTable");
    const tr = table.getElementsByTagName("tr");

    for (let i = 1; i < tr.length; i++) {
        const td = tr[i].getElementsByTagName("td")[columnIndex];
        if (td) {
            const textValue = td.textContent || td.innerText;
            tr[i].style.display = textValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
        }
    }
}

// Sort function
function sortTable(n) {
    const table = document.getElementById("dataTable");
    let rows, switching, i, x, y, shouldSwitch, dir, switchCount = 0;
    switching = true;
    dir = "asc"; // Set the sorting direction to ascending by default

    while (switching) {
        switching = false;
        rows = table.rows;

        // Loop through all table rows (except the first, which contains the headers)
        for (i = 1; i < (rows.length - 1); i++) {
            shouldSwitch = false;
            x = rows[i].getElementsByTagName("TD")[n];
            y = rows[i + 1].getElementsByTagName("TD")[n];

            // Check if the rows should switch place based on the direction, asc or desc
            if (dir === "asc") {
                if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir === "desc") {
                if (x.innerHTML.toLowerCase() < y.innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            }
        }

        if (shouldSwitch) {
            // If a switch has been marked, make the switch and mark switching as true
            rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
            switching = true;
            switchCount++; // Each time a switch is done, increase this count
        } else {
            // If no switching has been done AND the direction is "asc", switch to "desc"
            if (switchCount === 0 && dir === "asc") {
                dir = "desc";
                switching = true;
            }
        }
    }
}

//searchbar
document.getElementById('searchInput').addEventListener('keyup', function() {
        const filter = this.value.toUpperCase();
        const table = document.getElementById('dataTable');
        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {  // Skip header row
            let td = tr[i].getElementsByTagName('td');
            let match = false;

            for (let j = 0; j < td.length - 1; j++) {  // Skip 'Action' column
                if (td[j] && td[j].textContent.toUpperCase().indexOf(filter) > -1) {
                    match = true;
                    break;
                }
            }

            tr[i].style.display = match ? '' : 'none';
        }
    });



// Function to set the student ID for deletion
function setDeleteId(studentId) {
    selectedStudentId = studentId; // Assign the student ID to the variable
    console.log("Selected Student ID:", selectedStudentId); // For debugging
}

// Function to handle the delete action
document.getElementById('deleteButton').addEventListener('click', () => {
    if (selectedStudentId === null) {
        console.error('No student selected for deletion.');
        return;
    }

    // Example: Send a request to delete the student
    fetch('../functions/administrator/delete-gallery.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ studentId: selectedStudentId })
    })
    .then(response => {
    // Log the response to inspect it
    console.log('Response:', response);

    // Check if the response is OK (status code 200)
    if (!response.ok) {
        throw new Error('Network response was not ok');
    }

    return response.json(); // Attempt to parse as JSON
})
.then(data => {
    console.log('Data received:', data);
    if (data.success) {
        console.log('Student deleted successfully.');
    } else {
        console.error('Failed to delete student:', data.message);
    }
})
.catch(error => {
    console.error('Error during fetch or JSON parsing:', error);
});
});


</script>
    
</body>

</html>