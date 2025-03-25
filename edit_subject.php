<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if user is logged in
if(!isLoggedIn()) {
    redirect('index.php');
}

// Check if ID is provided
if(!isset($_GET['id']) || empty($_GET['id'])) {
//     setFlashMessage('error_msg', 'Subject ID is required', 'alert-danger');
    redirect('manage_subjects.php');
}

$id = intval($_GET['id']);

// Get subject details
$db = new Database();
$db->query("SELECT * FROM subjects WHERE id = :id");
$db->bind(':id', $id);
$subject = $db->single();

// Check if subject exists
if(!$subject) {
    setFlashMessage('error_msg', 'Subject not found', 'alert-danger');
    redirect('manage_subjects.php');
}

// Process edit form
if(isset($_POST['update'])) {
    $subject_name = sanitizeInput($_POST['subject_name']);
    $subject_code = sanitizeInput($_POST['subject_code']);
    
    // Validate inputs
    $errors = array();
    
    if(empty($subject_name)) {
        $errors[] = 'Subject name is required';
    }
    
    if(empty($subject_code)) {
        $errors[] = 'Subject code is required';
    }
    
    // Check if subject code already exists
    if(!empty($subject_code) && $subject_code != $subject['subject_code']) {
        $db = new Database();
        $db->query("SELECT COUNT(*) as count FROM subjects WHERE subject_code = :subject_code AND id != :id");
        $db->bind(':subject_code', $subject_code);
        $db->bind(':id', $id);
        $result = $db->single();
        
        if($result['count'] > 0) {
            $errors[] = 'Subject code already exists';
        }
    }
    
    // If no errors, proceed with update
    if(empty($errors)) {
        $db = new Database();
        $db->query("UPDATE subjects SET subject_name = :subject_name, subject_code = :subject_code WHERE id = :id");
        $db->bind(':subject_name', $subject_name);
        $db->bind(':subject_code', $subject_code);
        $db->bind(':id', $id);
        
        if($db->execute()) {
            setFlashMessage('success_msg', 'Subject updated successfully', 'alert-success');
            redirect('manage_subjects.php');
        } else {
            $errors[] = 'Failed to update subject. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Subject - SRMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="wrapper">
        <!-- Navigation -->
        <?php include('includes/navbar.php'); ?>
        
        <!-- Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-4">Edit Subject</h2>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-edit mr-1"></i>
                                    Subject Details
                                </div>
                                <div>
                                    <a href="manage_subjects.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Back to Manage Subjects
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(isset($errors) && !empty($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach($errors as $error): ?>
                                            <li><?php echo $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            
                            <form action="" method="post">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject_name">Subject Name <span class="text-danger">*</span></label>
//                                             <input type="text" class="form-control" id="subject_name" name="subject_name" value="<?php echo isset($_POST['subject_name']) ? $_POST['subject_name'] : $subject['subject_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject_code">Subject Code <span class="text-danger">*</span></label>
//                                             <input type="text" class="form-control" id="subject_code" name="subject_code" value="<?php echo isset($_POST['subject_code']) ? $_POST['subject_code'] : $subject['subject_code']; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" name="update" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="manage_subjects.php" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
