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

$result = $pdo->query("SELECT * FROM notes WHERE license='$currentUserLicense' ORDER BY id DESC");

//GET AMOUNT OF NOTES
$notes = $pdo->query("SELECT * FROM notes WHERE license='$currentUserLicense'");

if (!$result)
{
	$_SESSION['error'] = $pdo->errorInfo();
	header('Location: 404.php');
	die();
}
?>

							<div class="col-lg-5">
                                <div class="card-box">
                                	<span class="badge badge-success badge-pill float-right"><?php echo sizeof($notes->fetchAll()) ?></span>
                                    <h4 class="header-title">Notes</h4>
                                    <div class="table-responsive">
                                        <table class="table table-striped mb-0">
                                            <thead>
                                            <tr>
                                                <th>Staff</th>
                                                <th>Note</th>
                                                <th>Date</th>
												<th></th>
                                            </tr>
                                            </thead>
                                            <tbody>
										<?php
											foreach($result as $row)
											{
												$date_time = gmdate("d.m.Y h:i:s A", $row['time']);
												
												echo  '<td>'. $row['staff_name'] .'</td>
													  <td>'. $row['reason'] .'</td>
													  <td>'. $date_time .'</td>';
												if (in_array('RemoveNote', $_SESSION['permissionranks']))
												{
												echo '<td><a id="accentcolor" href="actions/player-actions-handler.php?noteID=' . $row['ID'] . '">Remove</a></td>';
												echo  '</tr>';
												}
											}
										?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

