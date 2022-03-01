<?php
require_once(__DIR__ . "/../config.php");

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$time = gmmktime();

foreach ($SERVERS as $server)
{
    $server_ip = $server['server_ip'];
    $server_port = $server['server_port'];

	$playerArray = file_get_contents('http://' . $server_ip . ':' . $server_port . '/players.json');
	$DolphinsArray = json_decode($playerArray, true);
	foreach ($DolphinsArray as $key => $value) {
	    $stmt = $pdo->prepare("INSERT INTO players (name, license, steam, firstjoined, lastplayed) VALUES (?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE name=VALUES(name), playtime=playtime+1, steam='".$value['identifiers'][0]."', lastplayed='$time'");
	        $result = $stmt->execute(array($value['name'], $value['identifiers'][1], $value['identifiers'][0], $time, $time));
	}
}
?>