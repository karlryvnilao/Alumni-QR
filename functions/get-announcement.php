<?php
require_once 'connection.php';

function get_announcement()
{
    global $db;
    $sql = "SELECT * FROM `announcements` ORDER BY `created_at` DESC LIMIT 6";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
?>
        <div class="col">
            <div class="card shadow-sm" data-bss-hover-animate="pulse">
                <img class="card-img-top w-100 d-block fit-cover" style="height: 100px;" src="https://student.lemerycolleges.edu.ph/images/favicon.png">
                <div class="card-body p-4">
                    <p class="text-primary card-text mb-0"><?php echo $row['created_at']?></p>
                    <h4 class="card-title">LC - Announcement</h4>
                    <p class="card-text"><?php echo $row['description']?></p>
                    <div class="d-flex"><img class="rounded-circle flex-shrink-0 me-3 fit-cover" width="50" height="50" src="https://student.lemerycolleges.edu.ph/images/favicon.png">
                        <div>
                            <p class="fw-bold mb-0">Administrator</p>
                            <p class="text-muted mb-0">LC - Announcement</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
