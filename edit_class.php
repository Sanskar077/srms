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
//     setFlashMessage('error_msg', 'Class ID is required', 'alert-danger');
    redirect('manage_classes.php');
}

$id = intval($_GET['id']);

// Get class details
$db = new Database();
$db->query("SELECT * FROM classes WHERE id = :id");
$db->bind(':id', $id);
$class = $db->single();

// Check if class exists
if(!$class) {
    setFlashMessage('error_msg', 'Class not found', 'alert-danger');
    redirect('manage_classes.php');
}

// Process edit form
if(isset($_POST['update'])) {
    $class_name = sanitizeInput($_POST['class_name']);
    $section = sanitizeInput($_POST['section']);
    
    // Validate inputs
    $errors = array();
    
    if(empty($class_name)) {
        $errors[] = 'Class name is required';
    }
    
    // If no errors, proceed with update
    if(empty($errors)) {
        $db = new Database();
        $db->query("UPDATE classes SET class_name = :class_name, section = :section WHERE id = :id");
        $db->bind(':class_name', $class_name);
        $db->bind(':section', $section);
        $db->bind(':id', $id);
        
        if($db->execute()) {
            setFlashMessage('success_msg', 'Class updated successfully', 'alert-success');
            redirect('manage_classes.php');
        } else {
            $errors[] = 'Failed to update class. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Class - SRMS</title>
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
                    <h2 class="mt-4 mb-4">Edit Class</h2>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-edit mr-1"></i>
                                    Class Details
                                </div>
                                <div>
                                    <a href="manage_classes.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Back to Manage Classes
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
                                            <label for="class_name">Class Name <span class="text-danger">*</span></label>
//                                             <input type="text" class="form-control" id="class_name" name="class_name" value="<?php echo isset($_POST['class_name']) ? $_POST['class_name'] : $class['class_name']; ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="section">Section</label>
                                            <input type="text" class="form-control" id="section" name="section" value="<?php echo isset($_POST['section']) ? $_POST['section'] : $class['section']; ?>">
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" name="update" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="manage_classes.php" class="btn btn-secondary">
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
