<?php ob_start(); ?>
<?php ob_start(); ?>
<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if user is logged in
if(!isLoggedIn()) {
    redirect('index.php');
}

// Get all students with class information
$db = new Database();
$db->query("SELECT s.*, c.class_name 
            FROM students s 
            LEFT JOIN classes c ON s.class_id = c.id 
            ORDER BY s.id");
$students = $db->resultSet();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="students_export_'.date('Y-m-d').'.csv"');

// Create a file pointer
$output = fopen('php://output', 'w');

// Write the column headers
fputcsv($output, array('ID', 'Name', 'Roll ID', 'Email', 'Class', 'Class ID', 'Gender', 'DOB', 'Registration Date'));

// Loop through data and write to CSV
foreach($students as $student) {
    $row = array(
        $student['id'],
        $student['name'],
        $student['roll_id'],
        $student['email'],
        $student['class_name'],
        $student['class_id'],
        $student['gender'],
        $student['dob'],
        $student['reg_date']
    );
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
