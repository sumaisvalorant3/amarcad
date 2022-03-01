<?php
require_once(__DIR__ . "/config.php");
session_start();

$serverName = $_GET['servername'];

foreach ($SERVERS as $server)
{
    if ($serverName == $server['server_name'])
    {
        $server_name = $server['server_name'];
        $server_ip = $server['server_ip'];
        $server_port = $server['server_port'];
    }
}

// GET PLAYERS WHO ARE ON THE SERVER
$playerArray = file_get_contents('http://' . $server_ip . ':' . $server_port . '/players.json');
$DolphinsArray = json_decode($playerArray, true);

try{
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
    echo "Could not connect -> ".$ex->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo SERVER_NAME ." | ". $server_name; ?></title>
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
                                    <h4 class="page-title"><?php echo $server_name; ?> Player List</h4>
                                </div>
                            </div>
                        </div>    

                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">Current Players In-Game</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap">
                                            <thead>
                                                <tr>
                                                  <th>ID</th>
                                                  <th>Name</th>
                                                  <th>Playtime</th>
                                                  <th>Trustscore</th>
                                                  <th>Ping</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                        <?php
                                              foreach ($DolphinsArray as $key => $value) {
                                                $currentlicense = $value["identifiers"][1];
                                                $userID = "";
                                                $result2 = $pdo->query("SELECT * FROM players WHERE license='$currentlicense'");
                                                foreach($result2 as $row)
                                                {
                                                    $userID = $row['ID'];
                                                    $userTrustscore = $row['trustscore'];
                                                    $userPlaytime = $row['playtime'];

                                                    $d = floor ($userPlaytime / 1440);
                                                    $h = floor (($userPlaytime - $d * 1440) / 60);
                                                    $m = $userPlaytime - ($d * 1440) - ($h * 60);
                                                    $playtimeString = "{$d} D, {$h} H, {$m} M";
                                                }
                                                echo '<td>'. $value["id"] .'</td>
                                                  <td><a id="accentcolor" href="user.php?id=' . $userID . '">'. $value["name"] .'</a></td>
                                                  <td>'. $playtimeString .'</td>
                                                  <td>'. $userTrustscore .'</td>
                                                  <td>'. $value["ping"] .'</td>
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