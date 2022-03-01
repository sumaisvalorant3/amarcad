<?php
session_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/q3query.class.php");
require_once(__DIR__ . "/discord_functions.php");
require_once(__DIR__ . "/trustscore.php");

$playerID = $_SESSION['playerID'];

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

if (isset($_POST['warn_player_btn']))
{
    warnPlayer();
	updateTrustScore("warn", $playerID);
}
if (isset($_POST['kick_player_btn']))
{
    kickPlayer();
	updateTrustScore("kick", $playerID);
}
if (isset($_POST['ban_player_btn']))
{
    banPlayer();
	updateTrustScore("ban", $playerID);
}
if (isset($_POST['commend_player_btn']))
{
    commendPlayer();
	updateTrustScore("commend", $playerID);
}
if (isset($_POST['note_player_btn']))
{
    addNotePlayer();
}
if (isset($_POST['strike_player_btn']))
{
    addStrikeStaff();
}
if (isset($_GET['banID']))
{
    removeBan();
	updateTrustScore("unban", $playerID);
}
if (isset($_GET['warningID']))
{
    removeWarning();
	updateTrustScore("unwarn", $playerID);
}
if (isset($_GET['kickID']))
{
    removeKick();
	updateTrustScore("unkick", $playerID);
}
if (isset($_GET['commendID']))
{
    removeCommend();
	updateTrustScore("uncommend", $playerID);
}
if (isset($_GET['noteID']))
{
    removeNote();
}
if (isset($_GET['strikeID']))
{
    removeStrike();
}
if (isset($_GET['oldnameID']))
{
	removeOldName();
}
if (isset($_GET['staffID']))
{
	removeStaff();
}

function warnPlayer()
{
	global $pdo, $SERVERS;
	// SHOULD BE ASSIGNED WHEN THE MOD/STAFF OPENED THE USERS PROFILE TO SEND THE ACTION
	$currentPlayerLicense = $_SESSION["currentPlayerLicense"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['warning']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

    $stmt = $pdo->prepare("INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute(array($currentPlayerLicense, $reason, $staffName, $staffSteamID, $time));


    if (!$result)
    {
		print_r($pdo->errorInfo());
        header('Location: ../404.php?Failed');
        die();
    }
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];
	    $server_rcon_pass = $server['server_rcon_pass'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$gameID = false;
		$playersName = $_SESSION["currentPlayerName"];
		
		$DolphinsArray = json_decode($playerArray, true);
		foreach ($DolphinsArray as $key => $value) {
			if($value["identifiers"][1] == $currentPlayerLicense){
				$gameID = $value["id"];
				$playersName = $value["name"];
			}
		}
		
		if($gameID === false)
		{
			//They aren't here
			header('Location: ../user.php?actionFailed&id=' . $playerID);
		}else {
			$con = new q3query($server_ip, $server_port, $success);
			if (!$success) {
				die ("Something has gone wrong.");
			}
			$con->setServerPort($server_port);
			$con->setRconpassword($server_rcon_pass);
			
			$con->rcon("say ^2" . $playersName . " ^0has been ^8warned ^0by " . $staffName . " ^0for ^3" . $reason);
			
			header('Location: ../user.php?actionSuccess&id=' . $playerID);
		}

	}

	$log = new richEmbed("NEW WARNING", "**{$playersName}** has been warned for ` {$reason} `");
	$log->addField("By user:", $staffName, true);
	$log = $log->build();
	sendLog($log, WARN_LOGS);
}

function kickPlayer()
{

	global $pdo, $SERVERS;
	// SHOULD BE ASSIGNED WHEN THE MOD/STAFF OPENED THE USERS PROFILE TO SEND THE ACTION
	$currentPlayerLicense = $_SESSION["currentPlayerLicense"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['kick']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

    $stmt = $pdo->prepare("INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute(array($currentPlayerLicense, $reason, $staffName, $staffSteamID, $time));

		
    if (!$result)
    {
		print_r($pdo->errorInfo());
        header('Location: ../404.php?Failed');
        die();
    }
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];
	    $server_rcon_pass = $server['server_rcon_pass'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$gameID = false;
		$playersName = $_SESSION["currentPlayerName"];
		
		$DolphinsArray = json_decode($playerArray, true);
		foreach ($DolphinsArray as $key => $value) {
			if($value["identifiers"][1] == $currentPlayerLicense){
				$gameID = $value["id"];
				$playersName = $value["name"];
			}
		}
	
		if($gameID === false)
		{
			header('Location: ../user.php?actionFailed&id=' . $playerID);
		}else {
			$con = new q3query($server_ip, $server_port, $success);
			if (!$success) {
				die ("Something has gone wrong.");
			}
			$con->setServerPort($server_port);
			$con->setRconpassword($server_rcon_pass);
			$con->rcon("clientkick " . $gameID . " Kick reason: " . $reason);
			
			$con->rcon("say ^2" . $playersName . " ^0has been ^1kicked ^0by " . $staffName . " ^0for ^3" . $reason);
			
			header('Location: ../user.php?actionSuccess&id=' . $playerID);
		}

	}

	$log = new richEmbed("NEW KICK", "**{$playersName}** has been kicked for ` {$reason} `");
	$log->addField("By user:", $staffName, true);
	$log = $log->build();
	sendLog($log, KICK_LOGS);
}

function banPlayer()
{
	global $pdo, $SERVERS;
		
	// SHOULD BE ASSIGNED WHEN THE MOD/STAFF OPENED THE USERS PROFILE TO SEND THE ACTION
	$currentPlayerLicense = $_SESSION["currentPlayerLicense"];
	$currentPlayerName = $_SESSION["currentPlayerName"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['ban']);
	$banDays = intval(htmlspecialchars($_POST['days'])) * 86400;
	$banHours = intval(htmlspecialchars($_POST['hours']) * 3600);
	$banMinutes = intval(htmlspecialchars($_POST['minutes']) * 60);

	$chatBanD = htmlspecialchars($_POST['days']);
    $chatBanH = htmlspecialchars($_POST['hours']);
    $chatBanM = htmlspecialchars($_POST['minutes']);

	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	// NOW WE TAKE THE CURRENT TIME AND ADD ON THE BAN TIME TO MAKE THE BANNED_UNTIL VARIABLE
	$bannedUntil;
	if($banDays == 0 && $banHours == 0 && $banMinutes == 0)
	{
		if (in_array('AddPermBan', $_SESSION['permissionranks']))
		{
			$bannedUntil = 0;
		} else {
			$noPerm = 1;
		}
	} else{
		$bannedUntil = intval($time) + intval($banDays) + intval($banHours) + intval($banMinutes);
	}

	if ($noPerm == 1)
	{
		header('Location: ../user.php?noPermBan&id=' . $playerID);
	} else {

	    $stmt = $pdo->prepare("INSERT INTO bans (name, identifier, reason, staff_name, staff_steamid, ban_issued, banned_until) VALUES (?, ?, ?, ?, ?, ?, ?)");
	    $result = $stmt->execute(array($currentPlayerName, $currentPlayerLicense, $reason, $staffName, $staffSteamID, $time, $bannedUntil));
			
	    if (!$result)
	    {
			print_r($pdo->errorInfo());
	        header('Location: ../404.php?Failed');
	        die();
	    }
		
		foreach ($SERVERS as $server)
		{
		    $server_ip = $server['server_ip'];
		    $server_port = $server['server_port'];
		    $server_rcon_pass = $server['server_rcon_pass'];

			$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
			$gameID = false;
			$playersName = $_SESSION["currentPlayerName"];
			
			$DolphinsArray = json_decode($playerArray, true);
			foreach ($DolphinsArray as $key => $value) {
				if($value["identifiers"][1] == $currentPlayerLicense){
					$gameID = $value["id"];
					$playersName = $value["name"];
				}
			}
		
			if($gameID === false)
			{
				header('Location: ../user.php?actionSuccess&id=' . $playerID);
			}else {
				$con = new q3query($server_ip, $server_port, $success);
				if (!$success) {
					die ("Something has gone wrong.");
				}
				$con->setServerPort($server_port);
				$con->setRconpassword($server_rcon_pass);
				$con->rcon("clientkick " . $gameID . " Ban reason: " . $reason);
				
				$con->rcon("say ^2" . $playersName . " ^0has been ^1banned ^0by " . $staffName . " ^0for ^3" . $reason . " ^0for ^3" . $chatBanD . "^0 Days ^3" . $chatBanH . "^0 Hours ^3" . $chatBanM . "^0 Minutes ^3");
				
				header('Location: ../user.php?actionSuccess&id=' . $playerID);
			}

		}

	    $banDays = htmlspecialchars($_POST['days']);
	    $banHours = htmlspecialchars($_POST['hours']);
	    $banMinutes = htmlspecialchars($_POST['minutes']);

	    $log = new richEmbed("NEW BAN", "**{$playersName}** has been banned for ` {$reason} `");
	    $log->addField("By user:", $staffName, true);
	    $log->addField("Length:", "{$banDays} days, {$banHours} hours, {$banMinutes} minutes", true);
	    $log = $log->build();
	    sendLog($log, BAN_LOGS);

	}

}

function commendPlayer()
{
	global $pdo, $SERVERS;
	// SHOULD BE ASSIGNED WHEN THE MOD/STAFF OPENED THE USERS PROFILE TO SEND THE ACTION
	$currentPlayerLicense = $_SESSION["currentPlayerLicense"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['commend']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

    $stmt = $pdo->prepare("INSERT INTO commend (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute(array($currentPlayerLicense, $reason, $staffName, $staffSteamID, $time));

		
    if (!$result)
    {
		print_r($pdo->errorInfo());
        header('Location: ../404.php?Failed');
        die();
    }
	
	// GET THE LIST OF PLAYERS, FIND THE PLAYERS IN GAME ID BY COMPARING THE LICENSE, IF THEY ARE IN THERE, THEY WILL BE COMMENDED PUBLICALLY.
	// LATER I CAN DIRECT BACK TO THE USER PAGE WITH A SUCCESS OR "USER ISN'T IN THE SERVER".
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];
	    $server_rcon_pass = $server['server_rcon_pass'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$gameID = false;
		$playersName = $_SESSION["currentPlayerName"];
		
		$DolphinsArray = json_decode($playerArray, true);
		foreach ($DolphinsArray as $key => $value) {
			if($value["identifiers"][1] == $currentPlayerLicense){
				$gameID = $value["id"];
				$playersName = $value["name"];
			}
		}
	
		if($gameID === false)
		{
			//They aren't here
			header('Location: ../user.php?actionFailed&id=' . $playerID);
		}else {
			$con = new q3query($server_ip, $server_port, $success);
			if (!$success) {
				die ("Something has gone wrong.");
			}
			$con->setServerPort($server_port);
			$con->setRconpassword($server_rcon_pass);
			
			$con->rcon("say ^2" . $playersName . " ^0has been ^2commended ^0by " . $staffName . " ^0for ^3" . $reason);
			header('Location: ../user.php?actionSuccess&id=' . $playerID);
		}

	}

	$log = new richEmbed("NEW COMMEND", "**{$playersName}** has been commended for ` {$reason} `");
	$log->addField("By user:", $staffName, true);
	$log = $log->build();
	sendLog($log, COMMEND_LOGS);

}

function addNotePlayer()
{
	global $pdo;
	// SHOULD BE ASSIGNED WHEN THE MOD/STAFF OPENED THE USERS PROFILE TO SEND THE ACTION
	$currentPlayerLicense = $_SESSION["currentPlayerLicense"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['note']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];

	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

    $stmt = $pdo->prepare("INSERT INTO notes (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute(array($currentPlayerLicense, $reason, $staffName, $staffSteamID, $time));

    if (!$result)
    {
		print_r($pdo->errorInfo());
        header('Location: ../404.php?Failed');
        die();
    }
	
	$playersName = $_SESSION["currentPlayerName"];
	
	$result2 = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result2 as $row2)
	{
		$playersName = $row2['name'];
	}

	$log = new richEmbed("NEW NOTE", "**{$playersName}** has been noted for ` {$reason} `");
	$log->addField("By user:", $staffName, true);
	$log = $log->build();
	sendLog($log, NOTE_LOGS);
	
	header('Location: ../user.php?actionSuccess&id=' . $playerID);
}

function removeBan()
{
	global $pdo;

	$banID = htmlspecialchars($_GET['banID']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	$staffName = $_SESSION['staffname'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

	// GET BAN BEFORE DELETING IT SO IT CAN BE LOGGED
	$banData = "";
	$getBan = $pdo->query("SELECT * FROM bans WHERE ID='$banID'");
	foreach($getBan as $banRow)
	{
		$banData = $banRow['reason'];
	}

	$pdo->query("DELETE FROM bans WHERE ID='$banID'");

	$playersName = $_SESSION["currentPlayerName"];

	$result = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result as $row)
	{
		$playersName = $row['name'];
	}

    $log = new richEmbed("PLAYER UNBANNED", $playersName . " who was banned for ` ".$banData." ` has now been unbanned.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVEBAN_LOGS);
	header('Location: ../user.php?id=' . $playerID);
}

function removeWarning()
{
	global $pdo;

	$warningID = htmlspecialchars($_GET['warningID']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	$staffName = $_SESSION['staffname'];
	$reason = htmlspecialchars($_POST['warning']);
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

	// GET WARN BEFORE DELETING IT SO IT CAN BE LOGGED
	$warnData = "";
	$getWarn = $pdo->query("SELECT * FROM warnings WHERE ID='$warningID'");
	foreach($getWarn as $warnRow)
	{
		$warnData = $warnRow['reason'];
	}

	$pdo->query("DELETE FROM warnings WHERE ID='$warningID'");

	$playersName = $_SESSION["currentPlayerName"];

	$result = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result as $row)
	{
		$playersName = $row['name'];
	}

    $log = new richEmbed("WARNING REMOVED", $playersName . " who was warned for ` ".$warnData." ` has now been unwarned.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVEWARN_LOGS);
	header('Location: ../user.php?id=' . $playerID);
}

function removeKick()
{
	global $pdo;

	$kickID = htmlspecialchars($_GET['kickID']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	$staffName = $_SESSION['staffname'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

	// GET KICK BEFORE DELETING IT SO IT CAN BE LOGGED
	$kickData = "";
	$getKick = $pdo->query("SELECT * FROM kicks WHERE ID='$kickID'");
	foreach($getKick as $kickRow)
	{
		$kickData = $kickRow['reason'];
	}

	$pdo->query("DELETE FROM kicks WHERE ID='$kickID'");

	$playersName = $_SESSION["currentPlayerName"];

	$result = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result as $row)
	{
		$playersName = $row['name'];
	}

    $log = new richEmbed("KICK REMOVED", $playersName . " who was kicked for ` ".$kickData." ` has now been unkicked.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVEKICK_LOGS);
	header('Location: ../user.php?id=' . $playerID);
}

function removeCommend()
{
	global $pdo;

	$commendID = htmlspecialchars($_GET['commendID']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	$staffName = $_SESSION['staffname'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

	// GET COMMEND BEFORE DELETING IT SO IT CAN BE LOGGED
	$commendData = "";
	$getCommend = $pdo->query("SELECT * FROM commend WHERE ID='$commendID'");
	foreach($getCommend as $commendRow)
	{
		$commendData = $commendRow['reason'];
	}

	$pdo->query("DELETE FROM commend WHERE ID='$commendID'");

	$playersName = $_SESSION["currentPlayerName"];

	$result = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result as $row)
	{
		$playersName = $row['name'];
	}

    $log = new richEmbed("COMMEND REMOVED", $playersName . " who was commended for ` ".$commendData." ` has now been uncommended.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVECOMMEND_LOGS);
	header('Location: ../user.php?id=' . $playerID);
}

function removeNote()
{
	global $pdo;

	$noteID = htmlspecialchars($_GET['noteID']);
	
	//Just used for making sure we get back to the correct user profile
	$playerID = $_SESSION['playerID'];
	$staffName = $_SESSION['staffname'];

	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	// GET NOTE BEFORE DELETING IT SO IT CAN BE LOGGED
	$noteData = "";
	$getNote = $pdo->query("SELECT * FROM notes WHERE ID='$noteID'");
	foreach($getNote as $noteRow)
	{
		$noteData = $noteRow['reason'];
	}

	$pdo->query("DELETE FROM notes WHERE ID='$noteID'");

	$playersName = $_SESSION["currentPlayerName"];

	$result = $pdo->query("SELECT * FROM players WHERE ID='$playerID'");
	foreach($result as $row)
	{
		$playersName = $row['name'];
	}

    $log = new richEmbed("NOTE REMOVED", $playersName . " who was noted for ` ".$noteData." ` has now been unnoted.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVENOTE_LOGS);
	header('Location: ../user.php?id=' . $playerID);
}

function removeOldName()
{
	global $pdo;
	if (in_array('RemoveOldNames', $_SESSION['permissionranks']))
	{
		$playerID = $_SESSION['playerID'];
		$staffName = $_SESSION['staffname'];
		$oldnameID = htmlspecialchars($_GET['oldnameID']);

		$nameData = "";
		$getName = $pdo->query("SELECT * FROM oldnames WHERE ID='$oldnameID'");
		foreach($getName as $row)
		{
			$idData = $row['playerid'];
			$nameData = $row['name'];
		}

		$pdo->query("DELETE FROM oldnames WHERE ID='$oldnameID'");

		$result = $pdo->query("SELECT * FROM players WHERE ID='$idData'");
		foreach($result as $row)
		{
			$playersName = $row['name'];
		}

	    $log = new richEmbed("PLAYER OLD NAME DELETED", "` {$nameData} ` has now been removed from {$playersName}.");
	    $log->addField("By user:", $staffName, true);
	    $log = $log->build();
	    sendLog($log, REMOVEOLDNAME_LOGS);
		header('Location: ../user.php?id=' . $playerID);
	}
}

function removeStaff()
{
	global $pdo;
	if (in_array('RemoveStaff', $_SESSION['permissionranks']))
	{
		$staffName = $_SESSION['staffname'];
		$staffID = htmlspecialchars($_GET['staffID']);

		$staffData = "";
		$getName = $pdo->query("SELECT * FROM users WHERE ID='$staffID'");
		foreach($getName as $row)
		{
			$discordData = $row['steamid'];
		}

		$pdo->query("DELETE FROM users WHERE ID='$staffID'");

	    $log = new richEmbed("STAFF DELETED", "<@{$discordData}> has now been removed.");
	    $log->addField("By user:", $staffName, true);
	    $log = $log->build();
	    sendLog($log, REMOVESTAFF_LOGS);
		header('Location: ../staffStats.php');
	}
}

function addStrikeStaff()
{
	global $pdo;

	// SHOULD BE ASSIGNED AS THE PERSON WHOS GETTING THE STRIKE
	$StaffDiscordID = $_SESSION["currentStaffStatSearchedDiscordID"];
	$staffName = $_SESSION['staffname'];
	$staffSteamID = $_SESSION['staffid'];
	$reason = htmlspecialchars($_POST['strike']);
	
	//Just used for making sure we get back to the correct user profile
	$staffMemberID = $_SESSION['currentStaffStatSearchedID'];

	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;

    $stmt = $pdo->prepare("INSERT INTO strikes (staff_steamid, reason, senders_steamid, senders_name, time) VALUES (?, ?, ?, ?, ?)");
    $result = $stmt->execute(array($StaffDiscordID, $reason, $staffSteamID, $staffName, $time));

    if (!$result)
    {
		print_r($pdo->errorInfo());
        die();
    }
	
	$staffMembersName = $_SESSION['currentStaffStatSearchedName'];

    $log = new richEmbed("NEW STAFF STRIKE", "**{$staffMembersName}** has been striked for: ` {$reason} `");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, STRIKE_LOGS);
	
	//COMPLEX RETURN POST
	$_SESSION['OVERRIDEID'] = $staffMemberID;
	header('Location: ../staffProfile.php');
}

function removeStrike()
{
	global $pdo;

	$strikeID = htmlspecialchars($_GET['strikeID']);
	
	//Just used for making sure we get back to the correct user profile
	$staffMemberID = $_SESSION['currentStaffStatSearchedID'];
	$staffName = $_SESSION['staffname'];
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	// GET STRIKE BEFORE DELETING IT SO IT CAN BE LOGGED
	$strikeData = "";
	$getStrike = $pdo->query("SELECT * FROM strikes WHERE ID='$strikeID'");
	foreach($getStrike as $strikeRow)
	{
		$strikeData = $strikeRow['reason'];
	}

	$pdo->query("DELETE FROM strikes WHERE ID='$strikeID'");

	$staffMembersName = $_SESSION['currentStaffStatSearchedName'];

    $log = new richEmbed("STAFF STRIKE REMOVED", $staffMembersName . " who was striked for ` ".$strikeData." ` has now been unstriked.");
    $log->addField("By user:", $staffName, true);
    $log = $log->build();
    sendLog($log, REMOVESTRIKE_LOGS);
	
	//COMPLEX RETURN POST
	$_SESSION['OVERRIDEID'] = $staffMemberID;
	header('Location: ../staffProfile.php');
}


function messageWholeServer($message) {
	global $SERVERS;
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];
	    $server_rcon_pass = $server['server_rcon_pass'];

		$con = new q3query($server_ip, $server_port, $success);
		if (!$success) {
			die ("Something has gone wrong.");
		}
		$con->setServerPort($server_port);
		$con->setRconpassword($server_rcon_pass);
		$con->rcon("say " . $message);
	}
}


if (isset($_POST['broadcast_btn']))
{
	$broadcast = htmlentities($_POST['broadcast']);
	$servername = htmlentities(trim($_POST['servername']));
    sendBroadcast($broadcast, $servername);
}

function sendBroadcast($broadcast, $servername) {
	global $SERVERS;

	foreach ($SERVERS as $server)
	{
		$server_name = $server['server_name'];
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];
	    $server_rcon_pass = $server['server_rcon_pass'];

	    if (trim($server_name) == $servername)
	    {
			$con = new q3query($server_ip, $server_port, $success);
			$con->setServerPort($server_port);
			$con->setRconpassword($server_rcon_pass);
			$con->rcon("say ^1^*[Server Announcement]: ^0" . $broadcast);
			$log = new richEmbed("NEW BROADCAST", "{$broadcast}");
			$log = $log->build();
			sendLog($log, BROADCAST_LOGS);
			header('Location: ../index.php');
	    }
	}
}


?>