<?php
session_start();
ob_start();
error_reporting(0);
include('includes/config.php');
include('includes/database.php');
include('includes/functions.php');

// Check login
if (!isLoggedIn()) {
    redirect('index.php');
}

$successMsg = '';
$errorMsg = '';

// Process form submission
if (isset($_POST['submit'])) {
    // Clean and validate input
    $noticeTitle = sanitizeInput($_POST['noticeTitle']);
    $noticeDetails = sanitizeInput($_POST['noticeDetails']);
    
    // Validate inputs
    if (empty($noticeTitle) || empty($noticeDetails)) {
        $errorMsg = "Please fill all required fields";
    } else {
        // Insert notice into database
        $sql = "INSERT INTO tblnotice(noticeTitle, noticeDetails) VALUES(:title, :details)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':title', $noticeTitle, PDO::PARAM_STR);
        $query->bindParam(':details', $noticeDetails, PDO::PARAM_STR);
        
        if ($query->execute()) {
            $successMsg = "Notice added successfully";
        } else {
            $errorMsg = "Something went wrong. Please try again";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Add Notice - MSBTE SRMS</title>
    
    <!-- Custom fonts -->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    
    <!-- Custom styles -->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">
        
        <!-- Include Sidebar -->
        
        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            
            <!-- Main Content -->
            <div id="content">
                
                <!-- Include Navbar -->
                <?php include('includes/navbar.php'); ?>
                
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    
                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Add Notice</h1>
                    </div>
                    
                    <!-- Notice Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Notice Information</h6>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($successMsg)): ?>
                                <div class="alert alert-success">
                                    <?php echo $successMsg; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($errorMsg)): ?>
                                <div class="alert alert-danger">
                                    <?php echo $errorMsg; ?>
                                </div>
                            <?php endif; ?>
                            
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="noticeTitle">Notice Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="noticeTitle" name="noticeTitle" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="noticeDetails">Notice Details <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="noticeDetails" name="noticeDetails" rows="5" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fas fa-plus-circle"></i> Add Notice
                                    </button>
                                    <a href="manage_notices.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>
                <!-- /.container-fluid -->
                
            </div>
            <!-- End of Main Content -->
            
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; MSBTE Diploma College SRMS <?php echo date('Y'); ?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->
            
        </div>
        <!-- End of Content Wrapper -->
        
    </div>
    <!-- End of Page Wrapper -->
    
    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>
    
    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>
</body>
</html>