<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/../actions/discord_functions.php");

session_start();

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

//GET PLAYERS LICENSE
$staffList = $pdo->query("SELECT * FROM users WHERE avatar<>''");

?>
<style>
.card-box {
  border-radius: 0px !important;
}
</style>
<div class="row">
    <div class="col-12">
        <div class="card-box">
            <h4 class="header-title">Select Staff Member</h4>
            <form method="post" class="mt-3">
                <div class="row justify-content-center">
                    <div class="col-sm-8">
                        <div class="input-group">
                            <select name="staffID" id="stafflist" class="form-control btn btn-outline-info dropdown-toggle">
							<?php
								foreach($staffList as $row)
								{
									if($_POST['staffID'])
									{
										if($_POST['staffID'] == $row['ID'])
										{
											echo '<option selected="selected" value="' . $row['ID'] . '">' . $row['name'] . '</option>';
										}else
										{
											echo '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
										}
									}else
									{
										echo '<option value="' . $row['ID'] . '">' . $row['name'] . '</option>';
									}
								}
							?>
                            </select>
                            <span class="input-group-append">
								<input name="get_staffstats_button" type="submit" class="btn btn-outline-info" value="Get Profile">
                            </span>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>









<?php
if (isset($_POST['get_staffstats_button']) || $_SESSION['OVERRIDEID'])
{
    showStats();
}

function showStats()
{
global $pdo;
	
$currentID = $_POST['staffID'];

if($_SESSION['OVERRIDEID']){$currentID = $_SESSION['OVERRIDEID']; unset($_SESSION['OVERRIDEID']);} //OVERRIDE THE ID WITH THE ONE PASSED BACK THEN KILL IT

$_SESSION['currentStaffStatSearchedID'] = $currentID;

$staffProfile = $pdo->query("SELECT * FROM users WHERE ID='$currentID'");

foreach($staffProfile as $row)
{
	$avatar = $row['avatar'];
	$staffname = $row['name'];
	$discordID = $row['steamid'];
}

$_SESSION['currentStaffStatSearchedDiscordID'] = $discordID;
$_SESSION['currentStaffStatSearchedName'] = $staffname;

$warns = $pdo->query("SELECT * FROM warnings WHERE staff_steamid='$discordID'");
$kicks = $pdo->query("SELECT * FROM kicks WHERE staff_steamid='$discordID'");
$bans = $pdo->query("SELECT * FROM bans WHERE staff_steamid='$discordID'");
$commends = $pdo->query("SELECT * FROM commend WHERE staff_steamid='$discordID'");
	
	
	echo '<div class="row">
                            <div class="col-lg-3">
                                <div class="text-center card-box">
                                    <div class="pt-2 pb-2">
                                        <img src="' . $avatar . '" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">

                                        <h4 class="mt-3"><a class="text-dark">'. $staffname . '</a></h4>
                                        <p class="text-muted">Discord ID: ' . $discordID . '</p>

                                    </div> <!-- end .padding -->
                                </div> <!-- end card-box-->
                            </div> <!-- end col -->';
			// USERS STRIKES
		  ?>
<?php include "includes/staff-strikes.inc.php"; ?>
		  <?php
		echo '</div>';	

	echo '					<div class="row">
	                            <div class="card-box col-md-3 col-sm-6">
                                    <div class="mt-2">
                                        <h6 class="text-uppercase">Warns Issued <span class="float-right">'. sizeof($warns->fetchAll()) . '</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-box col-md-3 col-sm-6">
                                    <div class="mt-2">
                                        <h6 class="text-uppercase">Kicks Issued <span class="float-right">'. sizeof($kicks->fetchAll()) . '</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-box col-md-3 col-sm-6">
                                    <div class="mt-2">
                                        <h6 class="text-uppercase">Bans Issued <span class="float-right">'. sizeof($bans->fetchAll()) . '</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-box col-md-3 col-sm-6">
                                    <div class="mt-2">
                                        <h6 class="text-uppercase">Commends Issued <span class="float-right">'. sizeof($commends->fetchAll()) . '</span></h6>
                                        <div class="progress progress-sm m-0">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

}
?>
