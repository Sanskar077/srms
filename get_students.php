<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if user is logged in
if(!isLoggedIn()) {
    echo '<option value="">Please login</option>';
    exit;
}

// Check if class ID is provided
if(isset($_POST['class_id']) && !empty($_POST['class_id'])) {
    $class_id = intval($_POST['class_id']);
    
    // Get students for the selected class
    $db = new Database();
    $db->query("SELECT * FROM students WHERE class_id = :class_id ORDER BY name");
    $db->bind(':class_id', $class_id);
    $students = $db->resultSet();
    
    // Return options
    echo '<option value="">Select Student</option>';
    foreach($students as $student) {
        echo '<option value="'.$student['id'].'">'.$student['name'].' (Roll: '.$student['roll_id'].')</option>';
    }
} else {
    echo '<option value="">Select Class First</option>';
}
?>
