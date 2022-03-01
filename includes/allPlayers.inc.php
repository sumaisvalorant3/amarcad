<?php
require_once(__DIR__ . "/../config.php");
session_start();

$user_id = $_SESSION['id'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	$_SESSION['error'] = "Could not connect -> ".$ex->getMessage();
	$_SESSION['error_blob'] = $ex;
	header('Location: '.BASE_URL.'/plugins/error/index.php');
	die();
}

$result = $pdo->query("SELECT * FROM players");

if (!$result)
{
	$_SESSION['error'] = $pdo->errorInfo();
	header('Location: 404.php');
	die();
}

?>

				<div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h4 class="header-title">All Players</h4>

                                        <table id="basic-datatable" class="table dt-responsive nowrap">
                                            <thead>
                                                <tr>
												<th>ID</th>
                                                <th>Name</th>
                                                <th>License</th>
												<th>Steam</th>
												<th>Discord</th>
												<th>Trust Score</th>
												<th>Play Time</th>
                                                <th>Last Played</th>
                                                </tr>
                                            </thead>
                                        
                                            <tbody>
										<?php
											foreach($result as $row)
											{

												$playTime = "";

												$playTime = $row['playtime'];

												$d = floor ($playTime / 1440);
												$h = floor (($playTime - $d * 1440) / 60);
												$m = $playTime - ($d * 1440) - ($h * 60);
												$playtimeString = "{$d} D, {$h} H, {$m} M";

												$convertLastPlayed = gmdate("d.m.Y h:i", $row['lastplayed']);
															

												echo '<td>'. $row['ID'] .'</td>
													  <td><a id="accentcolor" href="user.php?id=' . $row['ID'] . '">'. $row['name'] .'</a></td>
													  <td>'. $row['license'] .'</td>
													  <td>'. $row['steam'] .'</td>
													  <td>'. $row['discord'] .'</td>
													  <td>'. ceil($row['trustscore']) .'%</td>
													  <td>'. $playtimeString .'</td>
													  <td>'. $convertLastPlayed .'</td>
													  </tr>';
												
											}
										?>
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>



