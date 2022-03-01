<?php
session_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/q3query.class.php");

if ($_GET['action'] == "playerjoined")
{
    playerJoined();
}

function playerJoined()
{
	$playerName = htmlspecialchars($_GET['name']);
	$playerLicense = htmlspecialchars($_GET['license']);
	$playerSteam = htmlspecialchars($_GET['steam']);
	$playerDiscord = htmlspecialchars($_GET['discord']);
	$sentsecret = htmlspecialchars($_GET['secret']);
	
	if (SECRET == $sentsecret)
	{
		try{
			$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
		} catch(PDOException $ex)
		{
			echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
		}
		
		$time = gmmktime();

		$result2 = $pdo->query("SELECT * FROM players WHERE license='$playerLicense'");
		foreach ($result2 as $row)
		{
			$playerID = $row['ID'];
			$oldName = $row['name'];
		}


	    $stmt = $pdo->prepare("INSERT INTO players (name, license, steam, discord, playtime, firstjoined, lastplayed) VALUES (?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), lastplayed='$time'");
	    $result = $stmt->execute(array($playerName, $playerLicense, $playerSteam, $playerDiscord, "0", $time, $time));
	
	    if (!$result) {
			echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
	    } else {
			echo json_encode(array("response" => "200", "message" => "Successfully added user into database."));
		}

		if ($oldName != $playerName && $oldName	!= null) {
		    $stmt = $pdo->prepare("INSERT INTO oldnames (playerid, name) VALUES (?, ?)");
		    $result3 = $stmt->execute(array($playerID, $oldName));
		}
	} else {
		echo "Wrong Secret";
	}
}


?>