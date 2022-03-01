<?php
require_once(__DIR__ . "/../config.php");
session_start();

$currentUserID = $_SESSION['playerID'];
$currentLicense = "";
$currentName = "";
$currentSteam = "";
$playTime = "";
$fjoin = "";
$lplay = "";

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

if (is_numeric($currentUserID))
{
    $result = $pdo->query("SELECT * FROM players WHERE ID='$currentUserID'");
}

foreach($result as $row)
{
	$currentName = $row['name'];
	$currentSteam = $row['steam'];
	$currentDiscord = $row['discord'];
	$playTime = $row['playtime'];
	$fjoin = $row['firstjoined'];
	$lplay = $row['lastplayed'];
	$currentLicense = $row['license'];
}

//SETTING THE PLAYERS LICENSE INTO A SESSION
$_SESSION["currentPlayerLicense"] = $currentLicense;
//SETTING THE PLAYERS NAME INTO A SESSION
$_SESSION["currentPlayerName"] = $currentName;

$d = floor ($playTime / 1440);
$h = floor (($playTime - $d * 1440) / 60);
$m = $playTime - ($d * 1440) - ($h * 60);
$playtimeString = "{$d} Days, {$h} Hours and {$m} Minutes";

$first_joined = gmdate("d.m.Y h:i:s A", $fjoin);
$last_played = gmdate("d.m.Y h:i:s A", $lplay);

//HERE IS WHERE WE ARE GOING TO TRY AND GET THE STEAM PROFILE LINK AND AVATAR
$data = json_decode(file_get_contents("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . STEAM_API . "&steamids=" . hexdec(strtoupper(str_replace('steam:', '', $currentSteam))) . ""));
$main = $data->response->players[0];
$steamavatar = $main->avatarfull;

$steamLink = "https://steamcommunity.com/profiles/" . hexdec(str_replace('steam:', '', $currentSteam)) . "";

?>
				<div class="row">
                            <div class="col-lg-3">
                                <div class="text-center card-box">
                                    <div class="pt-2 pb-2">
                                    	<a href="<?php echo $steamLink; ?>" target="_blank">
                                        <img src="<?php echo $steamavatar; ?>" class="rounded-circle img-thumbnail avatar-xl" alt="profile-image">
                                    	</a>
                                        <h4 class="mt-3"><a href="#" class="text-dark"><?php echo $currentName; ?></a></h4>
                                        <p class="text-muted"><?php echo $currentSteam; ?></p>
                                        <p class="text-muted"><?php echo $currentDiscord; ?></p>
                                        <p class="text-muted">Playtime: <?php echo $playtimeString; ?></p>
                                        <p class="text-muted">First Joined: <?php echo $first_joined; ?></p>
                                        <p class="text-muted">Last Played: <?php echo $last_played; ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php include "includes/player-moderation-buttons.inc.php"; ?>
                            <?php include "includes/player-notes.inc.php"; ?>
                </div>

<?php include "includes/player-warnings.inc.php"; ?>
<?php include "includes/player-kicks.inc.php"; ?>
<?php include "includes/player-bans.inc.php"; ?>
<?php include "includes/player-commends.inc.php"; ?>
