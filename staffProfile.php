<?php
session_start();
require_once(__DIR__ . "/config.php");

$name = $_SESSION['staffname'];
$staffRank = $_SESSION['staffrank'];
 
//ADMINS AND ABOVE ONLY
if(in_array('ViewStaffSection', $_SESSION['permissionranks']))
{
    // YOU CAN STAY :D
}
else
{
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SERVER_NAME; ?> | Staff Profiles</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A FiveM Admin Panel by Hamz#0001" name="description" />
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?php echo LOGS_IMAGE; ?>">

        <!-- CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

    </head>

    <body class="left-side-menu-dark">

        <!-- Begin page -->
        <div id="wrapper">

        <!--HEADER-->
        <?php include "includes/header.inc.php"; ?>

        <!--NAVBAR-->
        <?php include "includes/navbar.inc.php"; ?>

        <!-- FOOTER -->
        <?php include "includes/footer.inc.php"; ?>

        <!-- CONTENT -->
            <div class="content-page">
                <div class="content">
                    <div class="container-fluid"> 
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box">
                                    <div class="page-title-right">

                                    </div>
                                    <h4 class="page-title">Staff Profile</h4>
                                </div>
                            </div>
                        </div>  
                <?php include "includes/staffProfile.inc.php"; ?>   
                    </div>
            </div> 
        </div>
        <div class="rightbar-overlay"></div>

        <!-- JS -->
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/libs/morris-js/morris.min.js"></script>
        <script src="assets/js/pages/dashboard-4.init.js"></script>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/js/pages/jquery.chat.js"></script>
        <script src="assets/js/pages/jquery.todo.js"></script>
        <script src="assets/js/pages/widgets.init.js"></script>      
    </body>
</html>