<?php
session_start();
require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/actions/q3query.class.php");

$staffRank = $_SESSION['staffrank'];

//ADMINS AND ABOVE ONLY
if(in_array('ManageServerResources', $_SESSION['permissionranks']))
{
    // YOU CAN STAY :D
}
else
{
    header('Location: ../login.php');
}

$serverName = $_GET['servername'];

foreach ($SERVERS as $server)
{
    if ($serverName == $server['server_name'])
    {
        $server_name = $server['server_name'];
        $server_ip = $server['server_ip'];
        $server_port = $server['server_port'];
        $server_rcon_pass = $server['server_rcon_pass'];
    }
}

$infoArray = file_get_contents('http://' . $server_ip . ':' . $server_port . '/info.json');
$resourceArray = json_decode($infoArray, true);


function startResource($resourcename) {
    global $server_ip, $server_port, $server_rcon_pass;
    $con = new q3query($server_ip, $server_port, $success);
    $con->setServerPort($server_port);
    $con->setRconpassword($server_rcon_pass);
    $con->rcon("start " . $resourcename);
}

function stopResource($resourcename) {
    global $server_ip, $server_port, $server_rcon_pass;
    $con = new q3query($server_ip, $server_port, $success);
    $con->setServerPort($server_port);
    $con->setRconpassword($server_rcon_pass);
    $con->rcon("stop " . $resourcename);
}

function restartResource($resourcename) {
    global $server_ip, $server_port, $server_rcon_pass;
    $con = new q3query($server_ip, $server_port, $success);
    $con->setServerPort($server_port);
    $con->setRconpassword($server_rcon_pass);
    $con->rcon("restart hamz-panel");
    $con->rcon("restart " . $resourcename);
}

if (isset($_POST['start_btn']))
{
    $resourcename = htmlentities($_POST['resource']);
    startResource($resourcename);
}
if (isset($_POST['stop_btn']))
{
    $resourcename = htmlentities($_POST['resource']);
    stopResource($resourcename);
}
if (isset($_POST['restart_btn']))
{
    $resourcename = htmlentities($_POST['resource']);
    restartResource($resourcename);
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SERVER_NAME; ?> | Resources | <?php echo $server_name; ?> </title>
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
                                    <h4 class="page-title">Resources List | <?php echo $server_name; ?></h4>
                                </div>
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Resources</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                  <th>Resource Name</th>
                                                  <th></th>
                                                  <th></th>
                                                  <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                              foreach ($resourceArray["resources"] as $key) {
                                                echo '<td>'. $key .'</td>
                                                    <td>
                                                    <form action="#" method="post"> 
                                                     <input type="hidden" value='.$key.' name="resource">
                                                     <input class="btn btn-outline-info waves-effect waves-light" type="submit" name="start_btn" value="Start"/> 
                                                    </form> 
                                                    </td>

                                                    <td>
                                                    <form method="post"> 
                                                     <input type="hidden" value='.$key.' name="resource">
                                                     <input class="btn btn-outline-info waves-effect waves-light" type="submit" name="restart_btn" value="Restart"/> 
                                                    </form> 
                                                    </td>

                                                    <td>
                                                    <form method="post"> 
                                                     <input type="hidden" value='.$key.' name="resource">
                                                     <input class="btn btn-outline-info waves-effect waves-light" type="submit" name="stop_btn" value="Stop"/> 
                                                    </form> 
                                                    </td>                                                   
                                                    </tr>';
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