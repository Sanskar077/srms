<?php ob_start(); ?>
<?php ob_start(); ?>
<?php ob_start(); ?>
<?php
session_start();
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
include('includes/config.php');

if(strlen($_SESSION['alogin'])=="") {   
    header("Location: index.php"); 
} else {
    if(isset($_POST['exportResults'])) {
        try {
            // Set headers for CSV download
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="results_export_'.date('Y-m-d_H-i-s').'.csv"');

            // Create output stream
            $output = fopen('php://output', 'w');

            // Add headers
            fputcsv($output, array('Student ID', 'Student Name', 'Class', 'Subject', 'Marks'));

            // Fetch results data with student, class and subject details
            $sql = "SELECT r.StudentId, s.StudentName, c.ClassName, sub.SubjectName, r.Marks 
                    FROM tblresult r 
                    JOIN tblstudents s ON r.StudentId = s.StudentId 
                    JOIN tblclasses c ON r.ClassId = c.id 
                    JOIN tblsubjects sub ON r.SubjectId = sub.id
                    ORDER BY s.StudentId, sub.SubjectName";
            $query = $dbh->prepare($sql);
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_ASSOC);

            // Add data to CSV
            foreach($results as $result) {
                fputcsv($output, array(
                    $result['StudentId'],
                    $result['StudentName'],
                    $result['ClassName'],
                    $result['SubjectName'],
                    $result['Marks']
                ));
            }

            // Track bulk operation
            $sql = "INSERT INTO tblbulkoperations (operation_type, filename, status, records_processed) 
                    VALUES ('Export Results', :filename, 'Completed', :count)";
            $query = $dbh->prepare($sql);
            $query->execute([
                ':filename' => 'results_export_'.date('Y-m-d_H-i-s').'.csv',
                ':count' => count($results)
            ]);

            fclose($output);
            exit;

        } catch(Exception $e) {
            error_log("Bulk result export error: " . $e->getMessage());
            $error = "Error exporting data: " . $e->getMessage();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS | Bulk Export Results</title>
        <link rel="stylesheet" href="css/bootstrap.min.css" media="screen" >
        <link rel="stylesheet" href="css/font-awesome.min.css" media="screen" >
        <link rel="stylesheet" href="css/animate-css/animate.min.css" media="screen" >
        <link rel="stylesheet" href="css/lobipanel/lobipanel.min.css" media="screen" >
        <link rel="stylesheet" href="css/main.css" media="screen" >
        <script src="js/modernizr/modernizr.min.js"></script>
    </head>
    <body class="top-navbar-fixed">
        <div class="main-wrapper">
            <?php include('includes/topbar.php');?>
            <div class="content-wrapper">
                <div class="content-container">
                    <?php include('includes/leftbar.php');?>
                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h2 class="title">Bulk Export Results</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li class="active">Bulk Export Results</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <section class="section">
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-8 col-md-offset-2">
                                        <div class="panel">
                                            <div class="panel-heading">
                                                <div class="panel-title">
                                                    <h5>Export Results Data</h5>
                                                </div>
                                            </div>
                                            <?php if($error){?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="panel-body">
                                                <form method="post" class="form-horizontal">
                                                    <div class="form-group">
                                                        <div class="col-sm-12">
                                                            <button type="submit" name="exportResults" class="btn btn-success">
                                                                <i class="fa fa-download"></i> Export Results Data
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="mt-4">
                                                    <h4>Export Format Description</h4>
                                                    <p>The exported CSV file will contain the following columns:</p>
                                                    <ul>
                                                        <li>Student ID</li>
                                                        <li>Student Name</li>
                                                        <li>Class Name</li>
                                                        <li>Subject Name</li>
                                                        <li>Marks</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <script src="js/jquery/jquery-2.2.4.min.js"></script>
        <script src="js/bootstrap/bootstrap.min.js"></script>
        <script src="js/pace/pace.min.js"></script>
        <script src="js/lobipanel/lobipanel.min.js"></script>
        <script src="js/iscroll/iscroll.js"></script>
        <script src="js/main.js"></script>
    </body>
</html>
<?php } ?>