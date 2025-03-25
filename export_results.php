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

// Get all results with student, class, and subject information
$db = new Database();
$db->query("SELECT r.*, s.name as student_name, s.roll_id, 
            c.class_name, sub.subject_name 
            FROM results r 
            LEFT JOIN students s ON r.student_id = s.id 
            LEFT JOIN classes c ON r.class_id = c.id 
            LEFT JOIN subjects sub ON r.subject_id = sub.id 
            ORDER BY r.id");
$results = $db->resultSet();

// Set headers for CSV download
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="results_export_'.date('Y-m-d').'.csv"');

// Create a file pointer
$output = fopen('php://output', 'w');

// Write the column headers
fputcsv($output, array('ID', 'Student Name', 'Roll ID', 'Student ID', 'Class', 'Class ID', 'Subject', 'Subject ID', 'Marks', 'Posting Date'));

// Loop through data and write to CSV
foreach($results as $result) {
    $row = array(
        $result['id'],
        $result['student_name'],
        $result['roll_id'],
        $result['student_id'],
        $result['class_name'],
        $result['class_id'],
        $result['subject_name'],
        $result['subject_id'],
        $result['marks'],
        $result['posting_date']
    );
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
