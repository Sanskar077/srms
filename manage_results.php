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
    
    if(deleteResult($id)) {
        setFlashMessage('success_msg', 'Result deleted successfully', 'alert-success');
    } else {
        setFlashMessage('error_msg', 'Failed to delete result', 'alert-danger');
    }
    
    redirect('manage_results.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Results - SRMS</title>
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
                    <h2 class="mt-4 mb-4">Manage Results</h2>
                    
                    <?php echo getFlashMessage('success_msg'); ?>
                    <?php echo getFlashMessage('error_msg'); ?>
                    
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-table mr-1"></i>
                                    Results List
                                </div>
                                <div>
                                    <a href="add_result.php" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> Add Result
                                    </a>
                                    <a href="bulk_import_results.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-import"></i> Bulk Import
                                    </a>
                                    <a href="export_results.php" class="btn btn-info btn-sm">
                                        <i class="fas fa-file-export"></i> Export
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="resultTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Student Name</th>
                                            <th>Roll ID</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Marks</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $db = new Database();
                                        $db->query("SELECT r.*, s.name as student_name, s.roll_id, 
                                                   c.class_name, sub.subject_name 
                                                   FROM results r 
                                                   LEFT JOIN students s ON r.student_id = s.id 
                                                   LEFT JOIN classes c ON r.class_id = c.id 
                                                   LEFT JOIN subjects sub ON r.subject_id = sub.id 
                                                   ORDER BY r.id DESC");
                                        $results = $db->resultSet();
                                        
                                        $count = 1;
                                        foreach($results as $result) {
                                            echo '<tr>
                                                <td>'.$count.'</td>
                                                <td>'.$result['student_name'].'</td>
                                                <td>'.$result['roll_id'].'</td>
                                                <td>'.$result['class_name'].'</td>
                                                <td>'.$result['subject_name'].'</td>
                                                <td>'.$result['marks'].'</td>
                                                <td>
                                                    <a href="edit_result.php?id='.$result['id'].'" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <a href="manage_results.php?del='.$result['id'].'" class="btn btn-sm btn-danger" 
                                                       onclick="return confirm(\'Do you really want to delete this result?\');">
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
            $('#resultTable').DataTable({
                "order": [[ 0, "asc" ]],
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });
        });
    </script>
</body>
</html>
