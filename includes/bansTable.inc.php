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

$result = $pdo->query("SELECT * FROM bans ORDER BY id DESC");

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
                                        <h4 class="header-title">Ban Logs</h4>
                                        <table id="basic-datatable" class="table dt-responsive nowrap">
                                            <thead>
                                                <tr>
												<th>ID</th>
                                                <th>Name</th>
                                                <th>Reason</th>
                                                <th>Staff</th>
                                                <th>Ban Start</th>
												<th>Ban End</th>
                                                </tr>
                                            </thead>
                                            <tbody>
										<?php
											foreach($result as $row)
											{
												$currentlicense = $row['identifier'];
												$userID = "";
												
												$result2 = $pdo->query("SELECT ID FROM players WHERE license='$currentlicense'");
												foreach($result2 as $row2)
												{
													$userID = $row2['ID'];
												}
												
												$banStart = gmdate("d.m.Y h:i:s A", $row['ban_issued']);
												$banEnd = gmdate("d.m.Y h:i:s A", $row['banned_until']);
												
												if($row['banned_until'] == '0')
												{
													$banEnd = "Permanent";
												}
												
												echo '<td>'. $row['ID'] .'</td>
													  <td><a id="accentcolor" href="user.php?id=' . $userID . '">'. $row['name'] .'</a></td>
													  <td>'. $row['reason'] .'</td>
													  <td>'. $row['staff_name'] .'</td>
													  <td>'. $banStart .'</td>
													  <td>'. $banEnd .'</td>
													  </tr>';
												
											}
										?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

