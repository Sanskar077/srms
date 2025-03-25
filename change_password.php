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
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];
    $username = isset($_SESSION['username']) ? $_SESSION['username'] : '';
    
    // Validate inputs
    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errorMsg = "Please fill all required fields";
    } elseif ($newPassword != $confirmPassword) {
        $errorMsg = "New password and confirm password do not match";
    } elseif (strlen($newPassword) < 6) {
        $errorMsg = "Password must be at least 6 characters long";
    } else {
        // Verify current password
        $sql = "SELECT * FROM admin WHERE username = :username";
        $query = $dbh->prepare($sql);
        $query->bindParam(':username', $username, PDO::PARAM_STR);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_OBJ);
        
        if ($query->rowCount() > 0) {
            if (password_verify($currentPassword, $result->password)) {
                // Hash new password
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                
                // Update password
                $sql = "UPDATE admin SET password = :password WHERE username = :username";
                $query = $dbh->prepare($sql);
                $query->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
                $query->bindParam(':username', $username, PDO::PARAM_STR);
                
                if ($query->execute()) {
                    $successMsg = "Password changed successfully";
                } else {
                    $errorMsg = "Something went wrong. Please try again";
                }
            } else {
                $errorMsg = "Current password is incorrect";
            }
        } else {
            $errorMsg = "Admin account not found";
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
    <title>Change Password - MSBTE SRMS</title>
    
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
                        <h1 class="h3 mb-0 text-gray-800">Change Password</h1>
                    </div>
                    
                    <!-- Password Form -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Change Admin Password</h6>
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
                            
                            <form action="" method="post" id="passwordForm">
                                <div class="form-group">
                                    <label for="currentPassword">Current Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="newPassword">New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                    <small class="text-muted">Password must be at least 6 characters long</small>
                                </div>
                                
                                <div class="form-group">
                                    <label for="confirmPassword">Confirm New Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" name="submit" class="btn btn-primary">
                                        <i class="fas fa-key"></i> Change Password
                                    </button>
                                    <a href="dashboard.php" class="btn btn-secondary">
                                        <i class="fas fa-arrow-left"></i> Back to Dashboard
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
    
    <!-- Custom validation script -->
    <script>
    $(document).ready(function() {
        $('#passwordForm').on('submit', function(e) {
            var newPassword = $('#newPassword').val();
            var confirmPassword = $('#confirmPassword').val();
            
            if (newPassword != confirmPassword) {
                e.preventDefault();
                alert('New password and confirm password do not match');
                return false;
            }
            
            if (newPassword.length < 6) {
                e.preventDefault();
                alert('Password must be at least 6 characters long');
                return false;
            }
        });
    });
    </script>
</body>
</html>