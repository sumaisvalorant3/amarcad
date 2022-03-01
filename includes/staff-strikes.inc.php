<?php
require_once(__DIR__ . "/../config.php");
session_start();

$staffRank = $_SESSION['staffrank'];
$currentDiscordID = $_SESSION['currentStaffStatSearchedDiscordID'];
$currentName = $_SESSION['currentStaffStatSearchedName'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

$result = $pdo->query("SELECT * FROM strikes WHERE staff_steamid='$currentDiscordID' ORDER BY id DESC");
?>

<div class="col-lg-9">
    <div class="card">
        <div class="card-body">
        <button style="float: right; margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#strikeModal" class="btn btn-outline-info">Add Strike</button>
            <h4 class="header-title">Strikes</h4>
            <table id="basic-datatable" class="table dt-responsive nowrap">
                <thead>
                    <tr>
                    <th>Staff</th>
                    <th>Note</th>
					<th>Issuer</th>
                    <th>Date</th>
					<th></th>
                    </tr>
                </thead>
                <tbody>
			<?php
				foreach($result as $row)
				{
					$date_time = gmdate("d.m.Y h:i:s A", $row['time']);
					
					echo  '<td>'. $currentName .'</td>
						  <td>'. $row['reason'] .'</td>
						  <td>'. $row['senders_name'] . '</td>
						  <td>'. $date_time .'</td>';
					//FOR ADMINS AND ABOVE
					if(in_array('RemoveStrike', $_SESSION['permissionranks']))
					{
						echo '<td><a id="accentcolor" href="actions/player-actions-handler.php?strikeID=' . $row['ID'] . '">Remove</a></td>';
					}
					echo  '</tr>';
					
				}
			?>
                </tbody>
            </table>
        </div> 

	<!-- STRIKE MODAL -->
	<div id="strikeModal" tabindex="-1" role="dialog" aria-labelledby="strikeModalLabel" aria-hidden="true" class="modal fade text-left">
	<div role="document" class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header"><strong id="strikeModalLabel" class="modal-title">Add Strike</strong>
	<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
	</div>
	<div class="modal-body">
	<p>Please enter the reason for the strike below.</p>
	<form action="actions/player-actions-handler.php" method="POST" onsubmit="this.strike_player_btn.hidden = true;">
	<div class="form-group">
	<label>Strike</label>
	<input name="strike" type="text" placeholder="E.g - Abuse" class="form-control">
	</div>
	<div class="form-group">
	<input name="strike_player_btn" type="submit" value="Add Strike" class="btn btn-outline-info">
	</div>
	</form>
	</div>
	<div class="modal-footer">
	<button type="button" data-dismiss="modal" class="btn btn-secondary">Close</button>
	</div>
	</div>
	</div>
	</div>                                    
    </div> <!-- end card -->
</div><!-- end col-->

