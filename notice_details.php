<?php
<?php include('includes/header.php'); ?>
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

// Check if notice ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    redirect('manage_notices.php');
}

$noticeId = intval($_GET['id']);

// Fetch notice details
$sql = "SELECT * FROM tblnotice WHERE id = :id";
$query = $dbh->prepare($sql);
$query->bindParam(':id', $noticeId, PDO::PARAM_INT);
$query->execute();
$notice = $query->fetch(PDO::FETCH_OBJ);

// If notice not found, redirect
if (!$notice) {
    redirect('manage_notices.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Notice Details - MSBTE SRMS</title>
    
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
                        <h1 class="h3 mb-0 text-gray-800">Notice Details</h1>
                        <a href="manage_notices.php" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Notices
                        </a>
                    </div>
                    
                    <!-- Notice Details Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Notice Information</h6>
                            <span class="badge badge-info">
                                Posted on: <?php echo date('d-m-Y h:i A', strtotime($notice->creationDate)); ?>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <h4 class="font-weight-bold"><?php echo htmlentities($notice->noticeTitle); ?></h4>
                            </div>
                            
                            <div class="notice-content p-3 bg-light rounded">
                                <p><?php echo nl2br(htmlentities($notice->noticeDetails)); ?></p>
                            </div>
                            
                            <div class="mt-4">
                                <a href="manage_notices.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                                <a href="manage_notices.php?del=<?php echo $notice->id; ?>" class="btn btn-danger" onclick="return confirm('Do you really want to delete this notice?');">
                                    <i class="fas fa-trash"></i> Delete Notice
                                </a>
                            </div>
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
<?php include('includes/footer.php'); ?>
