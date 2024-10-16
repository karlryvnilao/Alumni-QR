<?php

function get_course_datatable(){
    global $db;
    $sql = 'SELECT * FROM `courses`';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($courses as $course) {
    ?>
    <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $course['name']?></td>
            <td><?php echo $course['created_at']?></td>
            <td class="text-center">
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                    data-id="<?php echo $course['id']?>"
                    data-name="<?php echo $course['name']?>"><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" data-id="<?php echo $course['id']?>"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    <?php
    }
}

function get_alumni_batches() {
    global $db; // Ensure you're using your database connection
    $sql = 'SELECT year FROM batches'; // Replace with your actual batch table
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($batches as $batch) {
        echo "<option value=\"{$batch['year']}\">{$batch['year']}</option>";
    }
}



function get_batch_datatable(){
    global $db;
    $sql = 'SELECT * FROM `batch`';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $batches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($batches as $batch) {
    ?>
    <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $batch['year']?></td>
            <td><?php echo $batch['created_at']?></td>
            <td class="text-center">
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                    data-id="<?php echo $batch['id']?>"
                    data-name="<?php echo $batch['year']?>"><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" data-id="<?php echo $batch['id']?>"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    <?php
    }
}

function get_students(){
    global $db;
    $sql = 'SELECT s.id AS student_id, s.firstname, s.lastname, s.birthdate, 
                   c.name AS course_name, b.year AS batch_name, 
                   m.major_name AS majors_name, u.id AS user_id, u.username, u.status, 
                   s.present_address 
            FROM `students` s
            LEFT JOIN `courses` c ON s.course = c.id
            LEFT JOIN `batch` b ON s.batch = b.id
            LEFT JOIN `majors` m ON s.major_id = m.id
            LEFT JOIN `users` u ON s.user_id = u.id 
            WHERE u.status = "approved"';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $student) {
        $age =  date_diff(date_create($student['birthdate']), date_create('now'))->y;
    ?>
        <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $student['firstname'].' '.$student['lastname'] ?></td>
            <td><?php echo $student['course_name']?></td>
            <td><?php echo $student['majors_name']?></td>
            <td><?php echo $student['batch_name']?></td>
            <td><?php echo $student['status']?></td>
            <td class="text-center">
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                data-id="<?php echo $student['user_id']?>"
                data-username="<?php echo $student['username']?>"
                data-firstname="<?php echo $student['firstname']?>"
                data-lastname="<?php echo $student['lastname']?>"
                data-course="<?php echo $student['course_name']?>"
                data-graduated="<?php echo $student['batch_name']?>"
                data-present="<?php echo $student['present_address']?>"
                ><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" 
                data-id="<?php echo $student['user_id']?>"
                ><i class="fas fa-trash"></i></button></td>
        </tr>
    <?php
    }
}

function get_declined_students(){
    global $db;
    $sql = 'SELECT s.id AS student_id, s.firstname, s.lastname, s.birthdate, 
                   c.name AS course_name, b.year AS batch_name, 
                   m.major_name AS majors_name, u.id AS user_id, u.username, u.status, 
                   s.present_address 
            FROM `students` s
            LEFT JOIN `courses` c ON s.course = c.id
            LEFT JOIN `batch` b ON s.batch = b.id
            LEFT JOIN `majors` m ON s.major_id = m.id
            LEFT JOIN `users` u ON s.user_id = u.id 
            WHERE u.status = "declined"';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $student) {
        $age =  date_diff(date_create($student['birthdate']), date_create('now'))->y;
    ?>
        <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $student['firstname'].' '.$student['lastname'] ?></td>
            <td><?php echo $student['course_name']?></td>
            <td><?php echo $student['majors_name']?></td>
            <td><?php echo $student['batch_name']?></td>
            <td><?php echo $student['status']?></td>
            <td class="text-center">
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                data-id="<?php echo $student['user_id']?>"
                data-username="<?php echo $student['username']?>"
                data-firstname="<?php echo $student['firstname']?>"
                data-lastname="<?php echo $student['lastname']?>"
                data-course="<?php echo $student['course_name']?>"
                data-graduated="<?php echo $student['batch_name']?>"
                data-present="<?php echo $student['present_address']?>"
                ><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" 
                data-id="<?php echo $student['user_id']?>"
                ><i class="fas fa-trash"></i></button></td>
        </tr>
    <?php
    }
}

function get_students_pending(){
    global $db;
    $sql = 'SELECT s.*, c.name AS course_name, b.year AS batch_name, u.id as user_id, u.* 
        FROM `students` s
        LEFT JOIN `courses` c ON s.course = c.id
        LEFT JOIN `batch` b ON s.batch = b.id
        LEFT JOIN `users` u ON s.user_id = u.id 
        WHERE u.status = "pending"';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $student) {
        $age =  date_diff(date_create($student['birthdate']), date_create('now'))->y;
    ?>
        <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo htmlspecialchars($student['firstname'].' '.$student['lastname']); ?></td>
            <td><?php echo htmlspecialchars($student['course_name']); ?></td>
            <td><?php echo htmlspecialchars($student['batch_name']); ?></td>
            <td><?php echo htmlspecialchars($student['status']); ?></td>
            <td class="text-center">
                <!-- Approve Button -->
                <button class="btn btn-outline-info mx-1 mb-1" type="button" data-bs-toggle="modal" data-bs-target="#approveModal"
                    data-id="<?php echo htmlspecialchars($student['user_id']); ?>"
                    data-email="<?php echo htmlspecialchars($student['email']); ?>">
                    <i class="fas fa-check"></i>
                </button>

                 <!-- Decline Button (New) -->
                 <button class="btn btn-outline-secondary mx-1" type="button" data-bs-toggle="modal" data-bs-target="#declineModal"
                    data-id="<?php echo htmlspecialchars($student['user_id']); ?>"
                    data-email="<?php echo htmlspecialchars($student['email']); ?>">
                    <i class="fas fa-times"></i>
                </button>


                <!-- View Button (New) -->
                <button class="btn btn-outline-primary mx-1" type="button" data-bs-toggle="modal" data-bs-target="#view"
                    data-file="files/<?php echo htmlspecialchars($student['file']); ?>">
                    <i class="fas fa-eye"></i>
                </button>


                <!-- Edit Button -->
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                data-id="<?php echo htmlspecialchars($student['user_id']); ?>"
                data-username="<?php echo htmlspecialchars($student['username']); ?>"
                data-firstname="<?php echo htmlspecialchars($student['firstname']); ?>"
                data-lastname="<?php echo htmlspecialchars($student['lastname']); ?>"
                data-course="<?php echo htmlspecialchars($student['course_name']); ?>"
                data-batch="<?php echo htmlspecialchars($student['batch_name']); ?>"
                data-present="<?php echo htmlspecialchars($student['present_address']); ?>"
                ><i class="fas fa-edit"></i></button>

                <!-- Delete Button -->
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" 
                data-id="<?php echo htmlspecialchars($student['user_id']); ?>"
                ><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    <?php
    }
}


function get_students_graduated(){
    global $db;
    $sql = 'SELECT g.*, c.name AS course_name FROM `graduates` g LEFT JOIN `courses` c ON g.course = c.id';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($students as $student) {
    ?>
        <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $student['id'] ?></td>
            <td><?php echo $student['fullname']?></td>
            <td><?php echo $student['course_name']?></td>
            <td><?php echo $student['graduated']?></td>
            <td class="text-center">
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                data-id="<?php echo $student['id']?>"
                data-fullname="<?php echo $student['fullname']?>"
                data-course="<?php echo $student['course_name']?>"
                data-graduated="<?php echo $student['graduated']?>"
                ><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" 
                data-id="<?php echo $student['id']?>"
                ><i class="fas fa-trash"></i></button></td>
        </tr>
    <?php
    }
}

function get_announcements(){
    global $db;
    $sql = 'SELECT * FROM `announcements`';
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $announcements = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($announcements as $announcement) {
    ?>
    <tr>
            <td><img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png"><?php echo $announcement['id']?></td>
            <td><?php echo $announcement['description']?></td>
            <td><?php echo $announcement['created_at']?></td>
            <td class="text-center">
                <a class="btn btn-outline-danger mx-1" type="button" href="comment.php?id=<?php echo $announcement['id']?>">View</a>
                <button class="btn btn-outline-warning mx-1" type="button" data-bs-target="#update" data-bs-toggle="modal" 
                    data-id="<?php echo $announcement['id']?>"
                    data-description="<?php echo $announcement['description']?>"><i class="fas fa-edit"></i></button>
                <button class="btn btn-outline-danger mx-1" type="button" data-bs-target="#delete" data-bs-toggle="modal" data-id="<?php echo $announcement['id']?>"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
    <?php
    }
}

function get_gallery() {
    global $db; // Ensure $db is available

    $sql = 'SELECT s.*, c.name AS course_name, b.year AS batch_name 
            FROM `students` s
            LEFT JOIN `courses` c ON s.course = c.id
            LEFT JOIN `batch` b ON s.batch = b.id
            WHERE s.status = "active"';

    $stmt = $db->prepare($sql);
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($students as $student) {
        ?>
        <tr>
            <td>
                <img class="rounded-circle me-2" width="30" height="30" src="https://student.lemerycolleges.edu.ph/images/favicon.png">
                <?php echo htmlspecialchars($student['firstname'].' '.$student['lastname']); ?>
            </td>
            <td><?php echo htmlspecialchars($student['course_name']); ?></td>
            <td><?php echo htmlspecialchars($student['batch_name']); ?></td>
            <td><?php echo htmlspecialchars($student['status']); ?></td>
            <td class="text-center">
                <!-- Update Button -->
                <button class="btn btn-warning" type="button" data-bs-toggle="modal" data-bs-target="#update" onclick="updateModal(<?= $student['id'] ?>)"><i class="fas fa-edit"></i></button>

                <!-- Delete Button -->
                <button class="btn btn-danger" type="button" data-bs-toggle="modal" data-bs-target="#delete" onclick="setDeleteId(<?= $student['id'] ?>)"><i class="fas fa-trash"></i></button>
            </td>
        </tr>
        <?php
    }
}


function getAchievements() {
    global $db; // Your PDO connection
    $query = "SELECT id, name FROM achievements"; // Adjust the query as needed
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as an associative array
}

 
