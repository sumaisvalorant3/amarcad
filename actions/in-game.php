<?php
session_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/trustscore.php");
require_once(__DIR__ . "/discord_functions.php");

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo "Could not connect -> ".$ex->getMessage();
	die();
}

if (isset($_GET['kick']))
{
    kickPlayer();
}
if (isset($_GET['warn']))
{
    warnPlayer();
}
if (isset($_GET['commend']))
{
    commendPlayer();
}
if (isset($_GET['ban']))
{
    banPlayer();
}
if (isset($_GET['trustscore']))
{
    getPlayerTrustscore();
}
if (isset($_GET['history']))
{
    getPlayerHistory();
}

function getPlayerTrustscore() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['trustscore']);
	$playersLicense = "";

	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
			}
		}
	}

	$getID = $pdo->query("SELECT ID,playtime FROM players WHERE license='$playersLicense'");
	foreach($getID as $row)
	{
		$playTime = $row['playtime'];
		$d = floor ($playTime / 1440);
		$h = floor (($playTime - $d * 1440) / 60);
		$m = $playTime - ($d * 1440) - ($h * 60);
		$playtimeString = "{$d} ^3Days, ^0{$h} ^3Hours and ^0{$m} ^3Minutes";
		echo "^3Trustscore: ^0" . getTrustScore($row['ID']) . "% ^3and Playtime: ^0" . $playtimeString;
	}
}

function getPlayerHistory() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['history']);
	$playersLicense = "";

	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
			}
		}
	}
	
	$warns = $pdo->query("SELECT * FROM warnings WHERE license='$playersLicense'");
	$commends = $pdo->query("SELECT * FROM commend WHERE license='$playersLicense'");

	$historyResponse1 = "^3Warnings: ^0" . sizeof($warns->fetchAll()) . " ^3| Commends: ^0" . sizeof($commends->fetchAll());
	echo $historyResponse1;
}


function kickPlayer() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['kick']); // THIS IS THE INGAME ID OF THE PERSON WE ARE KICKING
	$staffName = htmlspecialchars($_GET['name']); // THIS IS THE MOD/STAFF WHO ISSUED THE KICKINGS LICENSE
	$reason = htmlspecialchars($_GET['reason']); // THIS IS THE REASON FOR THE KICK
	$playersLicense = "license"; // THIS WILL BE SET IN THE PLAYER ARRAY
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
	
		$staff_steamid = "DISCORD NOT LINKED";
		
		foreach ($DolphinsArray as $key2 => $value2) {
			if($value2['name'] == $staffName)
			{
				foreach ($value2['identifiers'] as $key3 => $value3){
				$pieces = explode(":", $value3);
					if($pieces[0] == "discord")
					{
						$staff_steamid = $pieces[1];
					}
				}
			}
		}
	}

	$sentsecret = $_GET['secret'];

	if (SECRET == $sentsecret) {	

		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
				$playersName = $value["name"]; 

				$stmt = $pdo->prepare("INSERT INTO kicks (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
				$result = $stmt->execute(array($playersLicense, $reason, $staffName, $staff_steamid, $time));
				
				$getID = $pdo->query("SELECT ID FROM players WHERE license='$playersLicense'");
				foreach($getID as $row)
				{
					updateTrustScore("kick", $row['ID']);
				}

				sleep(1);
				
				echo "true";
			}
		}

		$log = new richEmbed("NEW KICK", "**{$playersName}** has been kicked for  `{$reason} `");
		$log->addField("By user:", $staffName, true);
		$log = $log->build();
		sendLog($log, KICK_LOGS);

    } else {
    	echo "Wrong Secret";
    }
}

function warnPlayer() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['warn']); // THIS IS THE INGAME ID OF THE PERSON
	$staffName = htmlspecialchars($_GET['name']); // THIS IS THE MOD/STAFF WHO ISSUED
	$reason = htmlspecialchars($_GET['reason']); // REASON
	$playersLicense = "license"; // THIS WILL BE SET IN THE PLAYER ARRAY
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = time() + 3600;
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
	
		$staff_steamid = "DISCORD NOT LINKED";
		
		foreach ($DolphinsArray as $key2 => $value2) {
			if($value2['name'] == $staffName)
			{
				foreach ($value2['identifiers'] as $key3 => $value3){
				$pieces = explode(":", $value3);
					if($pieces[0] == "discord")
					{
						$staff_steamid = $pieces[1];
					}
				}
			}
		}
	}

	$sentsecret = $_GET['secret'];

	if (SECRET == $sentsecret) {

		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
				$playersName = $value["name"];

				$stmt = $pdo->prepare("INSERT INTO warnings (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
				$result = $stmt->execute(array($playersLicense, $reason, $staffName, $staff_steamid, $time));
				
				$getID = $pdo->query("SELECT ID FROM players WHERE license='$playersLicense'");
				foreach($getID as $row)
				{
					updateTrustScore("warn", $row['ID']);
				}

				sleep(1);
				
				echo "true";
			}
		}

		$log = new richEmbed("NEW WARNING", "**{$playersName}** has been warned for  `{$reason} `");
		$log->addField("By user:", $staffName, true);
		$log = $log->build();
		sendLog($log, WARN_LOGS);

	} else {
		echo "false";
	}
}

function commendPlayer() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['commend']); // THIS IS THE INGAME ID OF THE PERSON WE ARE KICKING
	$staffName = htmlspecialchars($_GET['name']); // THIS IS THE MOD/STAFF WHO ISSUED THE KICKINGS LICENSE
	$reason = htmlspecialchars($_GET['reason']); // THIS IS THE REASON FOR THE KICK
	$playersLicense = "license"; // THIS WILL BE SET IN THE PLAYER ARRAY
	

	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
	
		$staff_steamid = "DISCORD NOT LINKED";
		
		foreach ($DolphinsArray as $key2 => $value2) {
			if($value2['name'] == $staffName)
			{
				foreach ($value2['identifiers'] as $key3 => $value3){
				$pieces = explode(":", $value3);
					if($pieces[0] == "discord")
					{
						$staff_steamid = $pieces[1];
					}
				}
			}
		}
	}

	$sentsecret = $_GET['secret'];

	if (SECRET == $sentsecret) {	
		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
				$playersName = $value["name"];

				$stmt = $pdo->prepare("INSERT INTO commend (license, reason, staff_name, staff_steamid, time) VALUES (?, ?, ?, ?, ?)");
				$result = $stmt->execute(array($playersLicense, $reason, $staffName, $staff_steamid, $time));
				
				$getID = $pdo->query("SELECT ID FROM players WHERE license='$playersLicense'");
				foreach($getID as $row)
				{
					updateTrustScore("commend", $row['ID']);
				}

				sleep(1);
				
				echo "true";
			}
		}

		$log = new richEmbed("NEW COMMEND", "**{$playersName}** has been commended for  `{$reason} `");
		$log->addField("By user:", $staffName, true);
		$log = $log->build();
		sendLog($log, COMMEND_LOG);

	} else {
		echo "false";
	}

}

function banPlayer() {
	global $pdo, $SERVERS;

	$playerID = htmlspecialchars($_GET['ban']); // THIS IS THE INGAME ID OF THE PERSON WE ARE KICKING
	$staffName = htmlspecialchars($_GET['name']); // THIS IS THE MOD/STAFF WHO ISSUED THE KICKINGS LICENSE
	$hours = htmlspecialchars($_GET['dur']);
	$reason = htmlspecialchars($_GET['reason']); // THIS IS THE REASON FOR THE KICK
	$playersLicense = "license"; // THIS WILL BE SET IN THE PLAYER ARRAY
	
	// IT WAS ALWAYS 1 HOUR BEHIND EVEN WITH TIMEZONE CHANGE SO FUCK IT
	$time = gmmktime() + 3600;
	
	$banHours = intval($hours * 3600);
	$bannedUntil = "";
	
	if($banHours == 0) {
		$bannedUntil = 0;
	} else{
		$bannedUntil = intval($time) + $banHours;
	}
	
	foreach ($SERVERS as $server)
	{
	    $server_ip = $server['server_ip'];
	    $server_port = $server['server_port'];

		$playerArray = file_get_contents('http://'. $server_ip . ':' . $server_port .'/players.json');
		$DolphinsArray = json_decode($playerArray, true);
	
		$staff_steamid = "DISCORD NOT LINKED";
		
		foreach ($DolphinsArray as $key2 => $value2) {
			if($value2['name'] == $staffName)
			{
				foreach ($value2['identifiers'] as $key3 => $value3){
				$pieces = explode(":", $value3);
					if($pieces[0] == "discord")
					{
						$staff_steamid = $pieces[1];
					}
				}
			}
		}
	}

	$sentsecret = $_GET['secret'];

	if (SECRET == $sentsecret) {	
		foreach ($DolphinsArray as $key => $value) {
			if($value["id"] == $playerID){
				$playersLicense = $value["identifiers"][1];
				$playersName = $value["name"];
				
				
				$stmt = $pdo->prepare("INSERT INTO bans (name, identifier, reason, ban_issued, banned_until, staff_name, staff_steamid) VALUES (?, ?, ?, ?, ?, ?, ?)");
				$result = $stmt->execute(array($playersName, $playersLicense, $reason, $time, $bannedUntil, $staffName, $staff_steamid));

				$getID = $pdo->query("SELECT ID FROM players WHERE license='$playersLicense'");
				foreach($getID as $row)
				{
					updateTrustScore("ban", $row['ID']);
				}

				sleep(1);
				
				echo "true";
			}
		}

		$log = new richEmbed("NEW BAN", "**{$playersName}** has been banned for `{$reason} `");
	    $log->addField("By user:", $staffName, true);
	    $log->addField("Hours:", $hours, true);
	    $log = $log->build();
	    sendLog($log, BAN_LOGS);

    }
	else {
		echo "false";
	}
}
?>
