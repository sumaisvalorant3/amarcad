<?php
require_once(__DIR__ . "/../config.php");
session_start();

$currentUserID = $_SESSION['playerID'];
$currentUserLicense = "";

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

//GET PLAYERS LICENSE
$Playerresult = $pdo->query("SELECT license FROM players WHERE ID='$currentUserID'");

foreach($Playerresult as $playerRow)
{
	$currentUserLicense = $playerRow['license'];
}

$result = $pdo->query("SELECT * FROM bans WHERE identifier='$currentUserLicense' ORDER BY id DESC");

//GET AMOUNT OF BANS
$bans = $pdo->query("SELECT * FROM bans WHERE identifier='$currentUserLicense'");

if (!$result)
{
	$_SESSION['error'] = $pdo->errorInfo();
	header('Location: 404.php');
	die();
}
?>

						<div class="row">
							<div class="col-lg-12">
                                <div class="card-box">
                                	<span class="badge badge-success badge-pill float-right"><?php echo sizeof($bans->fetchAll()) ?></span>
                                    <h4 class="header-title">Bans</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                            <tr>
												<th>Staff</th>
                                                <th>Reason</th>
                                                <th>Ban Start</th>
												<th>Ban End</th>
												<?php
												if(in_array('RemoveBan', $_SESSION['permissionranks']))
												{
												?>
												<th></th>
												<?php
												}
												?>
                                            </tr>
                                            </thead>
                                            <tbody>
										<?php
											foreach($result as $row)
											{												
												$banStart = gmdate("d.m.Y h:i:s A", $row['ban_issued']);
												$banEnd = gmdate("d.m.Y h:i:s A", $row['banned_until']);
												
												if($row['banned_until'] == '0')
												{
													$banEnd = "Permanent";
												}
												
												echo '<td>'. $row['staff_name'] .'</td>
													  <td>'. $row['reason'] .'</td>
													  <td>'. $banStart .'</td>
													  <td>'. $banEnd .'</td>';

													  if(in_array('RemoveBan', $_SESSION['permissionranks']))
													  {
														  echo '<td><a id="accentcolor" href="actions/player-actions-handler.php?banID=' . $row['ID'] . '">Remove</a></td>';
													  }
													  
												echo  '</tr>';
											}
										?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>