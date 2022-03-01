<?php
require_once(__DIR__ . "/config.php");
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SERVER_NAME; ?> | Commends</title>
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
                                    <h4 class="page-title">Commends</h4>
                                </div>
                            </div>
                        </div>    

                <?php include "includes/commendsTable.inc.php"; ?>

                    </div>
            </div> 
        </div>
        <div class="rightbar-overlay"></div>

        <!-- JS -->
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>
        <script src="assets/libs/morris-js/morris.min.js"></script>
        <script src="assets/libs/datatables/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables/dataTables.bootstrap4.js"></script>
        <script src="assets/libs/datatables/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables/responsive.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/datatables/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables/buttons.flash.min.js"></script>
        <script src="assets/libs/datatables/buttons.print.min.js"></script>
        <script src="assets/libs/datatables/dataTables.keyTable.min.js"></script>
        <script src="assets/libs/datatables/dataTables.select.min.js"></script>
        <script src="assets/js/pages/datatables.init.js"></script>
        
    </body>
</html>