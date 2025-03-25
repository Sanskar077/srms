<?php
<?php include('includes/header.php'); ?>
session_start();
ob_start();
error_reporting(0);
include('includes/config.php');
include('includes/database.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MSBTE Diploma College - Notice Board</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .notice-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        .notice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .notice-date {
            font-size: 0.8rem;
            color: #6c757d;
        }
        .header {
            background-color: #0d6efd;
            padding: 1rem 0;
            color: white;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>MSBTE Diploma College</h1>
                    <p class="lead mb-0">Student Notice Board</p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="index.php" class="btn btn-outline-light">Back to Home</a>
                    <a href="find-result.php" class="btn btn-outline-light ml-2">Find Result</a>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Important Notices</h2>
                
                <?php
                $sql = "SELECT * FROM tblnotice ORDER BY creationDate DESC";
                $query = $dbh->prepare($sql);
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                
                if ($query->rowCount() > 0) {
                    foreach ($results as $result) { ?>
                        <div class="card notice-card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo htmlentities($result->noticeTitle); ?></h5>
                                <span class="notice-date">Posted on: <?php echo date('d M Y h:i A', strtotime($result->creationDate)); ?></span>
                            </div>
                            <div class="card-body">
                                <p class="card-text"><?php echo htmlentities($result->noticeDetails); ?></p>
                            </div>
                        </div>
                <?php }
                } else { ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No notices available at this time.
                    </div>
                <?php } ?>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-12 text-center">
                <p>Copyright © <?php echo date('Y'); ?> MSBTE Student Result Management System</p>
                <p><small>Developed for MSBTE Diploma Colleges</small></p>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
<?php include('includes/footer.php'); ?>
