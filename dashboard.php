<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if user is logged in
if(!isLoggedIn()) {
    redirect('index.php');
}

// Get dashboard stats
$totalStudents = getTotalStudents();
$totalResults = getTotalResults();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MSBTE Diploma College Student Result Management System</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .clickable-card {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Navigation -->
        <?php include('includes/navbar.php'); ?>
        
        <!-- Content -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-4 mb-4">MSBTE Diploma College - Admin Dashboard</h2>
                    
                    <?php echo getFlashMessage('success_msg'); ?>
                    
                    <div class="row">
                        <!-- Students Card -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <a href="manage_students.php" class="text-decoration-none">
                                <div class="card bg-primary text-white h-100 clickable-card">
                                    <div class="card-body py-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase">Diploma Students</h6>
                                                <h1 class="display-4"><?php echo $totalStudents; ?></h1>
                                            </div>
                                            <i class="fas fa-users fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex">
                                        <span class="text-white">View Details
                                            <span class="ml-2"><i class="fas fa-arrow-circle-right"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Results Card -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <a href="manage_results.php" class="text-decoration-none">
                                <div class="card bg-success text-white h-100 clickable-card">
                                    <div class="card-body py-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase">MSBTE Results</h6>
                                                <h1 class="display-4"><?php echo $totalResults; ?></h1>
                                            </div>
                                            <i class="fas fa-chart-line fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex">
                                        <span class="text-white">View Details
                                            <span class="ml-2"><i class="fas fa-arrow-circle-right"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Classes Card -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <a href="manage_classes.php" class="text-decoration-none">
                                <div class="card bg-warning text-white h-100 clickable-card">
                                    <div class="card-body py-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase">Diploma Courses</h6>
                                                <h1 class="display-4"><?php 
                                                    $db = new Database();
                                                    $db->query("SELECT COUNT(*) as total FROM classes");
                                                    $result = $db->single();
                                                    echo $result['total'];
                                                ?></h1>
                                            </div>
                                            <i class="fas fa-chalkboard fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex">
                                        <span class="text-white">View Details
                                            <span class="ml-2"><i class="fas fa-arrow-circle-right"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Subjects Card -->
                        <div class="col-md-6 col-lg-3 mb-4">
                            <a href="manage_subjects.php" class="text-decoration-none">
                                <div class="card bg-danger text-white h-100 clickable-card">
                                    <div class="card-body py-5">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase">MSBTE Subjects</h6>
                                                <h1 class="display-4"><?php 
                                                    $db = new Database();
                                                    $db->query("SELECT COUNT(*) as total FROM subjects");
                                                    $result = $db->single();
                                                    echo $result['total'];
                                                ?></h1>
                                            </div>
                                            <i class="fas fa-book fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex">
                                        <span class="text-white">View Details
                                            <span class="ml-2"><i class="fas fa-arrow-circle-right"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Notices Card -->
                        <div class="col-md-12 mb-4">
                            <a href="manage_notices.php" class="text-decoration-none">
                                <div class="card bg-info text-white h-100 clickable-card">
                                    <div class="card-body py-4">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="text-uppercase">Notice Board</h6>
                                                <h1 class="display-4"><?php 
                                                    $db = new Database();
                                                    $db->query("SELECT COUNT(*) as total FROM tblnotice");
                                                    $result = $db->single();
                                                    echo ($result && isset($result['total'])) ? $result['total'] : 0;
                                                ?></h1>
                                            </div>
                                            <i class="fas fa-clipboard-list fa-3x"></i>
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex">
                                        <span class="text-white">Manage Notices
                                            <span class="ml-2"><i class="fas fa-arrow-circle-right"></i></span>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Recent Activities -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Students</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Roll ID</th>
                                                <th>Class</th>
                                                <th>Registration Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $db = new Database();
                                            $db->query("SELECT s.*, c.class_name FROM students s 
                                                        LEFT JOIN classes c ON s.class_id = c.id 
                                                        ORDER BY s.id DESC LIMIT 5");
                                            $students = $db->resultSet();
                                            
                                            if(count($students) > 0) {
                                                foreach($students as $student) {
                                                    echo '<tr>
                                                        <td>'.$student['name'].'</td>
                                                        <td>'.$student['roll_id'].'</td>
                                                        <td>'.$student['class_name'].'</td>
                                                        <td>'.$student['reg_date'].'</td>
                                                    </tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="4" class="text-center">No students found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Results</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Student</th>
                                                <th>Class</th>
                                                <th>Subject</th>
                                                <th>Marks</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $db = new Database();
                                            $db->query("SELECT r.*, s.name as student_name, c.class_name, sub.subject_name 
                                                        FROM results r 
                                                        LEFT JOIN students s ON r.student_id = s.id 
                                                        LEFT JOIN classes c ON r.class_id = c.id 
                                                        LEFT JOIN subjects sub ON r.subject_id = sub.id 
                                                        ORDER BY r.id DESC LIMIT 5");
                                            $results = $db->resultSet();
                                            
                                            if(count($results) > 0) {
                                                foreach($results as $result) {
                                                    echo '<tr>
                                                        <td>'.$result['student_name'].'</td>
                                                        <td>'.$result['class_name'].'</td>
                                                        <td>'.$result['subject_name'].'</td>
                                                        <td>'.$result['marks'].'</td>
                                                    </tr>';
                                                }
                                            } else {
                                                echo '<tr><td colspan="4" class="text-center">No results found</td></tr>';
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
