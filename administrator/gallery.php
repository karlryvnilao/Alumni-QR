<?php
include_once '../functions/get-batch.php';
include_once '../functions/administrator/get-data-table.php';
if (session_start() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('location: ../index.php');
}
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
    <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/Nunito.css">
    <link rel="stylesheet" href="../assets/fonts/fontawesome-all.min.css">
    <link rel="stylesheet" href="../assets/css/datatables.min.css">
    <link rel="stylesheet" href="../assets/css/Hero-Clean-images.css">
    <link rel="stylesheet" href="../assets/css/Lightbox-Gallery-baguetteBox.min.css">
    <link rel="stylesheet" href="../assets/css/Login-Form-Basic-icons.css">
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
                        <button class="btn btn-outline-primary mx-2 mb-2" type="button" data-bs-target="#add" data-bs-toggle="modal">Add Photo</button>
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
                    
                    <!-- File Input -->
                    <input class="form-control form-control mb-3" type="file" name="photo" required placeholder=" ">
                    
                    <!-- Submit Button -->
                    <button class="btn btn-primary w-100" type="submit">Add Photo</button>
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
</body>

</html>