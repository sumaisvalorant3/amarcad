<?php
require_once(__DIR__ . "/../actions/trustscore.php");
require_once(__DIR__ . "/../config.php");
session_start();

$staffRank = $_SESSION['staffrank']; 
$playerID = $_SESSION['playerID'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

$result = $pdo->query("SELECT * FROM oldnames WHERE playerid='$playerID' ORDER BY id DESC");
$oldnames = $pdo->query("SELECT * FROM oldnames WHERE playerid='$playerID'");

?>
<style>
.modal {
	margin-top: 200px;
}
</style>

        <div class="col-4">
                <div class="card-box">
                <h4 class="header-title">Moderation Controls</h4>
                <div class="button-list text-center mt-2">
				  <?php

					if(in_array('AddNote', $_SESSION['permissionranks']))
					{
						
						//NOTES
						echo '<button type="button" data-toggle="modal" data-target="#noteModal" class="btn btn-outline-info">Add Note</button>';
						echo '<!-- NOTE MODAL -->
							<div id="noteModal" tabindex="-1" role="dialog" aria-labelledby="noteModalLabel" aria-hidden="true" class="modal fade text-left">
							<div role="document" class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header"><strong id="commendModalLabel" class="modal-title">Add Note</strong>
							<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							</div>
							<div class="modal-body">
							<p>Please enter the note about the user.</p>
							<form action="actions/player-actions-handler.php" method="POST" onsubmit="this.note_player_btn.hidden = true;">
							<div class="form-group">
							<label>Note</label>
							<input name="note" type="text" placeholder="E.g - Attitude Problem" class="form-control">
							</div>
							<div class="form-group">
							<input name="note_player_btn" type="submit" value="Add Note" class="btn btn-outline-info">
							</div>
							</form>
							</div>
							</div>
							</div>
							</div>';
					}

					if(in_array('AddWarn', $_SESSION['permissionranks']))
					{
						
						//WARNING
						echo '<button type="button" data-toggle="modal" data-target="#warnModal" class="btn btn-outline-info">Warn</button>';
						echo '<!-- WARNING MODAL -->
							<div id="warnModal" tabindex="-1" role="dialog" aria-labelledby="warnModalLabel" aria-hidden="true" class="modal fade text-left">
							<div role="document" class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header"><strong id="warnModalLabel" class="modal-title">Issue Warning</strong>
							<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							</div>
							<div class="modal-body">
							<p>Please issue an accurate reason for the warning.</p>
							<form action="actions/player-actions-handler.php" method="POST" onsubmit="this.warn_player_btn.hidden = true;">
							<div class="form-group">
							<label>Warning Reason</label>
							<input name="warning" type="text" placeholder="E.g - Unrealistic driving" class="form-control">
							</div>
							<div class="form-group">
							<input name="warn_player_btn" type="submit" value="Warn Player" class="btn btn-outline-info">
							</div>
							</form>
							</div>
							</div>
							</div>
							</div>';
					}

					if(in_array('AddCommend', $_SESSION['permissionranks']))
					{
						
						//COMMEND
						echo '<button type="button" data-toggle="modal" data-target="#commendModal" class="btn btn-outline-info">Commend</button>';
						echo '<!-- COMMEND MODAL -->
							<div id="commendModal" tabindex="-1" role="dialog" aria-labelledby="commendModalLabel" aria-hidden="true" class="modal fade text-left">
							<div role="document" class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header"><strong id="commendModalLabel" class="modal-title">Issue Commendation</strong>
							<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							</div>
							<div class="modal-body">
							<p>Please issue an accurate reason for the commendation.</p>
							<form action="actions/player-actions-handler.php" method="POST" onsubmit="this.commend_player_btn.hidden = true;">
							<div class="form-group">
							<label>Commend Reason</label>
							<input name="commend" type="text" placeholder="E.g - Good RP, Character Development" class="form-control">
							</div>
							<div class="form-group">
							<input name="commend_player_btn" type="submit" value="Commend Player" class="btn btn-outline-info">
							</div>
							</form>
							</div>
							</div>
							</div>
							</div>';
					}

					if(in_array('AddKick', $_SESSION['permissionranks']))
					{

						//KICK
						echo '<button type="button" data-toggle="modal" data-target="#kickModal" class="btn btn-outline-info">Kick</button>';
						echo '<!-- KICKS MODAL -->
							<div id="kickModal" tabindex="-1" role="dialog" aria-labelledby="kickModalLabel" aria-hidden="true" class="modal fade text-left">
							<div role="document" class="modal-dialog">
							<div class="modal-content">
							<div class="modal-header"><strong id="kickModalLabel" class="modal-title">Issue Kick</strong>
							<button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							</div>
							<div class="modal-body">
							<p>Please issue an accurate reason for the kick.</p>
							<form action="actions/player-actions-handler.php" method="POST" onsubmit="this.kick_player_btn.hidden = true;">
							<div class="form-group">
							<label>Kick Reason</label>
							<input name="kick" type="text" placeholder="E.g - Fail RP" class="form-control">
							</div>
							<div class="form-group">
							<input name="kick_player_btn" type="submit" value="Kick Player" class="btn btn-outline-info">
							</div>
							</form>
							</div>
							</div>
							</div>
							</div>';
					}
				
					if(in_array('AddTempBan', $_SESSION['permissionranks']) || in_array('AddPermBan', $_SESSION['permissionranks']))
					{
						//BAN
					?>	
						<button type="button" data-toggle="modal" data-target="#banModal" class="btn btn-outline-info">Ban</button>
						<div id="banModal" tabindex="-1" role="dialog" aria-labelledby="banModalLabel" aria-hidden="true" class="modal fade text-left">
							  <div role="document" class="modal-dialog">
							  <div class="modal-content">
							  <div class="modal-header"><strong id="banModalLabel" class="modal-title">Issue Ban</strong>
							  <button type="button" data-dismiss="modal" aria-label="Close" class="close"><span aria-hidden="true">×</span></button>
							  </div>
							  <div class="modal-body">
							  <p>Please issue an accurate reason for the ban.</p>
							  <form action="actions/player-actions-handler.php" method="POST">
							  <div class="form-group">
							  <label>Ban Reason</label>
							  <input name="ban" type="text" placeholder="E.g - Racial Slurs" class="form-control">
							  </div>
							  <div class="form-group">
							  <label>Ban Length <?php if (in_array('AddPermBan', $_SESSION['permissionranks'])) { echo '(Permanent = All 0)'; } ?></label><br>
							  <label>Days</label>
							  <input name="days" class="form-control input-sm" type="number" min="0" value="0">
							  <label>Hours</label>
							  <input name="hours" class="form-control input-sm" type="number" min="0" value="0">
							  <label>Minutes</label>
							  <input name="minutes" class="form-control input-sm" type="number" min="0" value="0">
							  </div>
							  <div class="form-group">       
							  <input name="ban_player_btn" type="submit" value="Ban Player" class="btn btn-outline-info">
							  </div>
							  </form>
							  </div>
							  </div>
							  </div>
							  </div>
				  <?php
					}
				  ?>
				
              </div>
              </div>
             
                             
      		<div class="card-box">
      		    <div class="mt-2">
      		        <h6 class="text-uppercase">TRUSTSCORE <span class="float-right"><?php echo getTrustScore($_SESSION['playerID']) ?>%</span></h6>
      		        <div class="progress progress-sm m-0">
      		            <div class="progress-bar bg-info" role="progressbar" style="width: <?php echo getTrustScore($_SESSION['playerID']) ?>%" aria-valuenow="<?php echo getTrustScore($_SESSION['playerID']) ?>" aria-valuemin="0" aria-valuemax="100">
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if (in_array('ViewOldNames', $_SESSION['permissionranks']))
            {
            ?>
            <div class="card-box overflow-check" style="height: 11em;">
            	<span class="badge badge-success badge-pill float-right"><?php echo sizeof($oldnames->fetchAll()) ?></span>
                <h4 class="header-title">Old Names</h4>
                <div class="table-responsive text-center">
                    <table class="table table-striped mb-0">
                        <tbody>
					<?php
						foreach($result as $row)
						{
							$oldname = $row['name'];
					?>	
						<tr>
							<td></td>
							<td><?php echo $oldname; ?></td>
							<?php
							if (in_array('RemoveOldNames', $_SESSION['permissionranks']))
							{
							?>
							<td><a id="accentcolor" href="actions/player-actions-handler.php?oldnameID=<?php echo $row['ID']; ?>">Remove</a></td>
							<?php
							}
							?>
						</tr>
					<?php	
						}

						if (!empty($oldname))
						{
							echo '<style>.overflow-check {overflow-y: scroll;}</style>';
						} else {
							echo '<span><i>None</i></span>';
						}
					?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php
        	}
            ?>
		</div>