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
    if(isset($_POST['importStudents'])) {
        $targetDir = "uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $targetFile = $targetDir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $fileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));

        // Check file type
        if($fileType != "csv") {
            $error = "Only CSV files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 1) {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
                // Track bulk operation
                $sql = "INSERT INTO tblbulkoperations (operation_type, filename, status) VALUES (:type, :filename, 'Processing')";
                $query = $dbh->prepare($sql);
                $query->bindParam(':type', $type);
                $query->bindParam(':filename', $filename);
                $type = "Import Students";
                $filename = basename($_FILES["fileToUpload"]["name"]);
                $query->execute();
                $operationId = $dbh->lastInsertId();

                try {
                    $handle = fopen($targetFile, "r");
                    // Skip header row
                    fgetcsv($handle, 1000, ",");

                    $successCount = 0;
                    $dbh->beginTransaction();

                    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                        $sql = "INSERT INTO tblstudents (StudentName, RollId, StudentEmail, Gender, ClassId, DOB) 
                                VALUES (:name, :rollid, :email, :gender, :classid, :dob)";
                        $query = $dbh->prepare($sql);
                        $query->execute([
                            ':name' => $data[0],
                            ':rollid' => $data[1],
                            ':email' => $data[2],
                            ':gender' => $data[3],
                            ':classid' => $data[4],
                            ':dob' => $data[5]
                        ]);
                        $successCount++;
                    }

                    $dbh->commit();
                    fclose($handle);

                    // Update operation status
                    $sql = "UPDATE tblbulkoperations SET status = 'Completed', records_processed = :count WHERE id = :id";
                    $query = $dbh->prepare($sql);
                    $query->execute([':count' => $successCount, ':id' => $operationId]);

                    $msg = "Successfully imported " . $successCount . " students";
                    unlink($targetFile); // Delete uploaded file
                } catch (Exception $e) {
                    $dbh->rollBack();
                    $error = "Error processing file: " . $e->getMessage();
                    error_log("Bulk import error: " . $e->getMessage());

                    // Update operation status
                    $sql = "UPDATE tblbulkoperations SET status = 'Failed' WHERE id = :id";
                    $query = $dbh->prepare($sql);
                    $query->execute([':id' => $operationId]);
                }
            } else {
                $error = "Sorry, there was an error uploading your file.";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SRMS | Bulk Import Students</title>
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
                                    <h2 class="title">Bulk Import Students</h2>
                                </div>
                            </div>
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
                                        <li><a href="dashboard.php"><i class="fa fa-home"></i> Home</a></li>
                                        <li class="active">Bulk Import</li>
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
                                                    <h5>Import Students</h5>
                                                </div>
                                            </div>
                                            <?php if($msg){?>
                                                <div class="alert alert-success left-icon-alert" role="alert">
                                                    <strong>Well done!</strong><?php echo htmlentities($msg); ?>
                                                </div>
                                            <?php } else if($error){?>
                                                <div class="alert alert-danger left-icon-alert" role="alert">
                                                    <strong>Oh snap!</strong> <?php echo htmlentities($error); ?>
                                                </div>
                                            <?php } ?>
                                            <div class="panel-body">
                                                <form method="post" enctype="multipart/form-data" class="form-horizontal">
                                                    <div class="form-group">
                                                        <label class="col-sm-2 control-label">CSV File:</label>
                                                        <div class="col-sm-10">
                                                            <input type="file" name="fileToUpload" class="form-control" required>
                                                            <span class="help-block">Upload CSV file containing student data</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-sm-offset-2 col-sm-10">
                                                            <button type="submit" name="importStudents" class="btn btn-primary">Import Students</button>
                                                        </div>
                                                    </div>
                                                </form>

                                                <div class="mt-4">
                                                    <h4>Sample CSV Format</h4>
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Student Name</th>
                                                                <th>Roll ID</th>
                                                                <th>Email</th>
                                                                <th>Gender</th>
                                                                <th>Class ID</th>
                                                                <th>DOB</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>John Doe</td>
                                                                <td>12345</td>
                                                                <td>john@example.com</td>
                                                                <td>Male</td>
                                                                <td>1</td>
                                                                <td>2000-01-01</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
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