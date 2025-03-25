<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

$result_found = false;
$student_data = array();
$result_data = array();
$error_msg = '';
$success_msg = '';

// Process form
if(isset($_POST['submit'])) {
    $roll_id = sanitizeInput($_POST['roll_id']);
    $class_id = intval($_POST['class_id']);
    
    // Validate inputs
    if(empty($roll_id)) {
        $error_msg = 'Please enter Roll ID';
    } elseif(empty($class_id)) {
        $error_msg = 'Please select Class';
    } else {
        // Get student details
        $db = new Database();
        $db->query("SELECT s.*, c.class_name, c.section FROM students s 
                    LEFT JOIN classes c ON s.class_id = c.id 
                    WHERE s.roll_id = :roll_id AND s.class_id = :class_id");
        $db->bind(':roll_id', $roll_id);
        $db->bind(':class_id', $class_id);
        $student_data = $db->single();
        
        if($student_data) {
            // Get student's results
            $db->query("SELECT r.*, s.subject_name, s.subject_code 
                        FROM results r 
                        LEFT JOIN subjects s ON r.subject_id = s.id 
                        WHERE r.student_id = :student_id AND r.class_id = :class_id");
            $db->bind(':student_id', $student_data['id']);
            $db->bind(':class_id', $class_id);
            $result_data = $db->resultSet();
            
            if(count($result_data) > 0) {
                $result_found = true;
                $success_msg = 'Result found for ' . $student_data['name'];
            } else {
                $error_msg = 'No results found for this student';
            }
        } else {
            $error_msg = 'Student not found with the given Roll ID and Class';
        }
    }
}

// Get all classes for dropdown
$classes = getAllClasses();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Find Result - MSBTE Diploma College Student Result Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .result-container {
            max-width: 800px;
            margin: 50px auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .card-header {
            background-color: #4e73df;
            color: white;
            border-radius: 10px 10px 0 0 !important;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
        }
        .result-card {
            border: 1px solid #e3e6f0;
            border-radius: 0.35rem;
            margin-bottom: 2rem;
        }
        .result-card .student-info {
            padding: 1.5rem;
            background-color: #f8f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        .result-card .marks-table {
            padding: 1.5rem;
        }
        .result-card .total-marks {
            padding: 1rem 1.5rem;
            background-color: #f8f9fc;
            border-top: 1px solid #e3e6f0;
            font-weight: bold;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="result-container">
            <div class="card no-print">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0 logo">
                                <i class="fas fa-user-graduate mr-2"></i>
                                MSBTE SRMS
                            </h4>
                            <small>Diploma College Result System</small>
                        </div>
                        <div>
                            <a href="student_notices.php" class="btn btn-info btn-sm mr-2">
                                <i class="fas fa-clipboard-list"></i> Notice Board
                            </a>
                            <a href="index.php" class="btn btn-light btn-sm">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <h5 class="mb-4">Find Your Result</h5>
                    
                    <?php if(!empty($error_msg)): ?>
                        <div class="alert alert-danger" role="alert">
                            <?php echo $error_msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(!empty($success_msg)): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo $success_msg; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="roll_id">Roll ID</label>
//                                     <input type="text" class="form-control" id="roll_id" name="roll_id" value="<?php echo isset($_POST['roll_id']) ? $_POST['roll_id'] : ''; ?>" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="class_id">Class</label>
                                    <select class="form-control" id="class_id" name="class_id" required>
                                        <option value="">Select Class</option>
                                        <?php foreach($classes as $class): ?>
                                            <option value="<?php echo $class['id']; ?>" <?php echo (isset($_POST['class_id']) && $_POST['class_id'] == $class['id']) ? 'selected' : ''; ?>>
                                                <?php echo $class['class_name'] . ' - ' . $class['section']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">
                            <i class="fas fa-search mr-2"></i> Find Result
                        </button>
                    </form>
                </div>
            </div>
            
            <?php if($result_found): ?>
                <div class="result-card mt-4">
                    <div class="text-center py-4">
                        <h2 class="mb-0">MSBTE Diploma College</h2>
                        <p class="mb-0">Student Examination Result</p>
                    </div>
                    <div class="student-info">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Student Name:</strong> <?php echo $student_data['name']; ?></p>
                                <p><strong>Roll ID:</strong> <?php echo $student_data['roll_id']; ?></p>
                                <p><strong>Email:</strong> <?php echo $student_data['email']; ?></p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Class:</strong> <?php echo $student_data['class_name'] . ' - ' . $student_data['section']; ?></p>
                                <p><strong>Gender:</strong> <?php echo ($student_data['gender'] == 'M') ? 'Male' : 'Female'; ?></p>
                                <p><strong>Registration Date:</strong> <?php echo $student_data['reg_date']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="marks-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>Subject Code</th>
                                    <th>Marks</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $total_marks = 0;
                                $total_subjects = count($result_data);
                                
                                foreach($result_data as $result): 
                                    $total_marks += $result['marks'];
                                    $status = ($result['marks'] >= 35) ? 'Pass' : 'Fail';
                                    $status_class = ($result['marks'] >= 35) ? 'text-success' : 'text-danger';
                                ?>
                                    <tr>
                                        <td><?php echo $result['subject_name']; ?></td>
                                        <td><?php echo $result['subject_code']; ?></td>
                                        <td><?php echo $result['marks']; ?></td>
                                        <td class="<?php echo $status_class; ?>"><?php echo $status; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="total-marks">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-0">Total Marks: <?php echo $total_marks . ' / ' . ($total_subjects * 100); ?></p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p class="mb-0">Percentage: <?php echo number_format(($total_marks / ($total_subjects * 100)) * 100, 2); ?>%</p>
                            </div>
                        </div>
                    </div>
                    <div class="text-center p-3 no-print">
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i> Print Result
                        </button>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="text-center mt-3 text-muted no-print">
                <small>&copy; <?php echo date('Y'); ?> MSBTE Diploma College SRMS</small>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
