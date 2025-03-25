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
//     setFlashMessage('error_msg', 'Result ID is required', 'alert-danger');
    redirect('manage_results.php');
}

$id = intval($_GET['id']);
$result = getResultById($id);

// Check if result exists
if(!$result) {
    setFlashMessage('error_msg', 'Result not found', 'alert-danger');
    redirect('manage_results.php');
}

// Process edit form
if(isset($_POST['update'])) {
    $student_id = intval($_POST['student_id']);
    $class_id = intval($_POST['class_id']);
    $subject_id = intval($_POST['subject_id']);
    $marks = floatval($_POST['marks']);
    
    // Validate inputs
    $errors = array();
    
    if(empty($student_id)) {
        $errors[] = 'Student is required';
    }
    
    if(empty($class_id)) {
        $errors[] = 'Class is required';
    }
    
    if(empty($subject_id)) {
        $errors[] = 'Subject is required';
    }
    
    if(!is_numeric($marks) || $marks < 0 || $marks > 100) {
        $errors[] = 'Marks must be between 0 and 100';
    }
    
    // If no errors, proceed with update
    if(empty($errors)) {
        $data = array(
            'id' => $id,
            'student_id' => $student_id,
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'marks' => $marks
        );
        
        if(updateResult($data)) {
            setFlashMessage('success_msg', 'Result updated successfully', 'alert-success');
            redirect('manage_results.php');
        } else {
            $errors[] = 'Failed to update result. Please try again.';
        }
    }
}

// Get all classes for dropdown
$classes = getAllClasses();

// Get all subjects for dropdown
$subjects = getAllSubjects();

// Get students by class
$students = getStudentsByClass($result['class_id']);

// Get student details
$student = getStudentById($result['student_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Result - SRMS</title>
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
                    <h2 class="mt-4 mb-4">Edit Result</h2>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-edit mr-1"></i>
                                    Result Details
                                </div>
                                <div>
                                    <a href="manage_results.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-left"></i> Back to Manage Results
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
                                            <label for="class_id">Class <span class="text-danger">*</span></label>
                                            <select class="form-control" id="class_id" name="class_id" required>
                                                <option value="">Select Class</option>
                                                <?php foreach($classes as $class): ?>
                                                    <option value="<?php echo $class['id']; ?>" <?php 
                                                        if(isset($_POST['class_id'])) {
                                                            echo ($_POST['class_id'] == $class['id']) ? 'selected' : '';
                                                        } else {
                                                            echo ($result['class_id'] == $class['id']) ? 'selected' : '';
                                                        }
                                                    ?>>
                                                        <?php echo $class['class_name'] . ' - ' . $class['section']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="student_id">Student <span class="text-danger">*</span></label>
                                            <select class="form-control" id="student_id" name="student_id" required>
                                                <option value="">Select Student</option>
                                                <?php foreach($students as $s): ?>
                                                    <option value="<?php echo $s['id']; ?>" <?php 
                                                        if(isset($_POST['student_id'])) {
                                                            echo ($_POST['student_id'] == $s['id']) ? 'selected' : '';
                                                        } else {
                                                            echo ($result['student_id'] == $s['id']) ? 'selected' : '';
                                                        }
                                                    ?>>
                                                        <?php echo $s['name'] . ' (Roll: ' . $s['roll_id'] . ')'; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="subject_id">Subject <span class="text-danger">*</span></label>
                                            <select class="form-control" id="subject_id" name="subject_id" required>
                                                <option value="">Select Subject</option>
                                                <?php foreach($subjects as $subject): ?>
                                                    <option value="<?php echo $subject['id']; ?>" <?php 
                                                        if(isset($_POST['subject_id'])) {
                                                            echo ($_POST['subject_id'] == $subject['id']) ? 'selected' : '';
                                                        } else {
                                                            echo ($result['subject_id'] == $subject['id']) ? 'selected' : '';
                                                        }
                                                    ?>>
                                                        <?php echo $subject['subject_name']; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="marks">Marks <span class="text-danger">*</span></label>
//                                             <input type="number" class="form-control" id="marks" name="marks" value="<?php echo isset($_POST['marks']) ? $_POST['marks'] : $result['marks']; ?>" min="0" max="100" required>
                                            <small class="form-text text-muted">Marks should be between 0 and 100</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" name="update" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="manage_results.php" class="btn btn-secondary">
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
    <script>
        $(document).ready(function() {
            // Load students when class changes
            $('#class_id').change(function() {
                var classId = $(this).val();
                if(classId) {
                    $.ajax({
                        url: 'get_students.php',
                        type: 'POST',
                        data: {class_id: classId},
                        success: function(response) {
                            $('#student_id').html(response);
                        }
                    });
                } else {
                    $('#student_id').html('<option value="">Select Student</option>');
                }
            });
        });
    </script>
</body>
</html>
