<?php
session_start();
ob_start();
include('includes/config.php');
include('includes/functions.php');

// Check if user is logged in
if(!isLoggedIn()) {
    redirect('index.php');
}

// Handle delete request
if(isset($_GET['del']) && !empty($_GET['del'])) {
    $id = intval($_GET['del']);
    
    $db = new Database();
    
    // Check if subject has results
    $db->query("SELECT COUNT(*) as count FROM results WHERE subject_id = :id");
    $db->bind(':id', $id);
    $result = $db->single();
    
    if($result['count'] > 0) {
        setFlashMessage('error_msg', 'Cannot delete subject as it has results assigned to it', 'alert-danger');
    } else {
        // Delete subject
        $db->query("DELETE FROM subjects WHERE id = :id");
        $db->bind(':id', $id);
        
        if($db->execute()) {
            setFlashMessage('success_msg', 'Subject deleted successfully', 'alert-success');
        } else {
            setFlashMessage('error_msg', 'Failed to delete subject', 'alert-danger');
        }
    }
    
    redirect('manage_subjects.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Subjects - SRMS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
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
                    <h2 class="mt-4 mb-4">Manage Subjects</h2>
                    
                    <?php echo getFlashMessage('success_msg'); ?>
                    <?php echo getFlashMessage('error_msg'); ?>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table mr-1"></i>
                                    Subjects List
                                </div>
                                <div>
                                    <a href="add_subject.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add Subject
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="subjectTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Subject Name</th>
                                            <th>Subject Code</th>
                                            <th>Creation Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $db = new Database();
                                        $db->query("SELECT * FROM subjects ORDER BY id");
                                        $subjects = $db->resultSet();
                                        
                                        $count = 1;
                                        foreach($subjects as $subject) {
                                            echo '<tr>
                                                <td>'.$count.'</td>
                                                <td>'.$subject['subject_name'].'</td>
                                                <td>'.$subject['subject_code'].'</td>
                                                <td>'.$subject['created_at'].'</td>
                                                <td>
                                                    <a href="edit_subject.php?id='.$subject['id'].'" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="manage_subjects.php?del='.$subject['id'].'" class="btn btn-sm btn-danger" 
                                                       onclick="return confirm(\'Do you really want to delete this subject?\');">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </a>
                                                </td>
                                            </tr>';
                                            
                                            $count++;
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
    
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/js/script.js"></script>
    <script>
        $(document).ready(function() {
            $('#subjectTable').DataTable({
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        });
    </script>
</body>
</html>
