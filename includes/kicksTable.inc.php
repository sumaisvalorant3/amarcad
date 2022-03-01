<?php
require_once(__DIR__ . "/../config.php");
session_start();

$user_id = $_SESSION['id'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

$result = $pdo->query("SELECT * FROM kicks ORDER BY id DESC");

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
                                        <h4 class="header-title">Kick Logs</h4>

                                        <table id="basic-datatable" class="table dt-responsive nowrap">
                                            <thead>
                                                <tr>
												<th>ID</th>
                                                <th>Name</th>
                                                <th>Reason</th>
                                                <th>Staff</th>
                                                <th>Date</th>
                                                </tr>
                                            </thead>
                                        
                                            <tbody>
										<?php
											foreach($result as $row)
											{
												$currentlicense = $row['license'];
												$currentName = $row['license'];
												$userID = "";
												
												$result2 = $pdo->query("SELECT ID,name FROM players WHERE license='$currentlicense'");
												foreach($result2 as $row2)
												{
													$currentName = $row2['name'];
													$userID = $row2['ID'];
												}
												
												$date_time = gmdate("d.m.Y h:i:s A", $row['time']);
												
												echo '<td>'. $row['ID'] .'</td>
													  <td><a id="accentcolor" href="user.php?id=' . $userID . '">'. $currentName .'</a></td>
													  <td>'. $row['reason'] .'</td>
													  <td>'. $row['staff_name'] .'</td>
													  <td>'. $date_time .'</td>
													  </tr>';
												
											}
										?>
                                            </tbody>
                                        </table>

                                    </div> 
                                </div> 
                            </div>
                        </div>
