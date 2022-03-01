<?php
require_once(__DIR__ . "/config.php");

if(isset($_GET['unauthorised']))
{
  $actionMessage = '<div class="alert alert-danger alert-dismissible fade show" style="text-align: center;" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> Unauthorised access, this has been logged!</div>';
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SERVER_NAME; ?> | Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Coderthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="<?php echo LOGS_IMAGE; ?>">

        <!-- CSS -->
        <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="assets/css/app.min.css" rel="stylesheet" type="text/css" />

        <!--OPEN GRAPH FOR DISCORD RICH PRESENCE-->
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo BASE_URL; ?>" />
        <meta property="og:title" content="<?php echo SERVER_NAME; ?>" />
        <meta property="og:description" content="Staff Panel by Hamz#0001">
        <meta name="theme-color" content="<?php echo ACCENT_COLOR; ?>">

        <style>
            body {
                background: <?php echo ACCENT_COLOR; ?>
            }
        </style>
    </head>

    <body>
        <div class="account-pages" style="padding-top: 125px;">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5 mt-5">
                                <?php if($actionMessage){echo $actionMessage;} ?>
                        <div class="card">

                            <div class="card-body p-4">
                                
                                <div class="text-center w-75 m-auto">
                                    <a href="index.html">
                                        <span><img src=" <?php echo LOGS_IMAGE; ?> " alt="" height="60"></span>
                                    </a>
                                    <h4 class="text-muted mb-3 mt-3"><?php echo SERVER_NAME; ?></h4>
                                    <p class="text-muted mb-4 mt-3" title="****">You will need to login using discord.</p>
                                </div>

                                <form>
                                    <div class="form-group mb-0 text-center">
                                        <a class="btn btn-outline-info btn-block" type="button" href="actions/register.php">Log In</a>
                                    </div>
                                </form>

                            </div>
                            <p class="text-center mb-3">Made with <i class="mdi mdi-heart text-danger"></i> by <a style="color: <?php echo ACCENT_COLOR; ?>;" href="https://discord.gg/3DDWp6w">Hamz#0001</a> </p> 
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- JS -->
        <script src="assets/js/vendor.min.js"></script>
        <script src="assets/js/app.min.js"></script>   
    </body>
</html>