<?php
require_once(__DIR__ . "/../config.php");
session_start();

$user_id = $_SESSION['id'];

$staffRank = $_SESSION['staffrank'];
 
//ADMINS AND ABOVE ONLY
if(in_array('ViewStaffSection', $_SESSION['permissionranks']))
{
    // YOU CAN STAY :D
}
else
{
    header('Location: ../login.php');
}

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

$result = $pdo->query("SELECT * FROM users");

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
	                <h4 class="header-title">Staff Statistics</h4>
	                <table id="basic-datatable" class="table dt-responsive nowrap">
	                    <thead>
	                        <tr>
	                        <th>Name</th>
	                        <th>Discord ID</th>
	                        <th>Warns</th>
	                        <th>Kicks</th>
							<th>Bans</th>
	                        <th>Commends</th>
	                        <?php
							if (in_array('RemoveStaff', $_SESSION['permissionranks']))
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
							$currentID = $row['ID'];
							$currentName = $row['name'];
							$currentDiscordID = $row['steamid'];
							
							$warns = $pdo->query("SELECT * FROM warnings WHERE staff_steamid='$currentDiscordID'");
							$kicks = $pdo->query("SELECT * FROM kicks WHERE staff_steamid='$currentDiscordID'");
							$bans = $pdo->query("SELECT * FROM bans WHERE staff_steamid='$currentDiscordID'");
							$commends = $pdo->query("SELECT * FROM commend WHERE staff_steamid='$currentDiscordID'");
							
							$date_time = gmdate("d.m.Y h:i:s A", $row['time']);
							
							echo '<td><form action="staffProfile.php" method="post"><input type="hidden" name="staffID" value="' . $currentID . '"><input id="accentcolor" name="get_staffstats_button" type="submit" class="btn btn-primary" style="background: none!important;border: none;padding: 0!important;font-family: arial, sans-serif;color:#6658dd;" value="' . $currentName . '"></form></td>
								  <td>'. $currentDiscordID . '</td>
								  <td>'. sizeof($warns->fetchAll()) .'</td>
								  <td>'. sizeof($kicks->fetchAll()) .'</td>
								  <td>'. sizeof($bans->fetchAll()) .'</td>
								  <td>'. sizeof($commends->fetchAll()) .'</td>';
								  if (in_array('RemoveStaff', $_SESSION['permissionranks']))
								  {
								  echo'<td><a id="accentcolor" href="actions/player-actions-handler.php?staffID=' . $row['ID'] . '">Remove</a></td>';
								  }
								  echo'</tr>';
							
						}
					?>
	                    </tbody>
	                </table>
	            </div>
	        </div>
	    </div>
	</div>
