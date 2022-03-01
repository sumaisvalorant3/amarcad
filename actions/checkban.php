<?php
require_once(__DIR__ . "/../config.php");

$playerLicense = htmlspecialchars($_GET['license']);
$time = gmmktime();

try{
	$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
	echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
}

$result = $pdo->query("SELECT reason, ban_issued, banned_until, staff_name FROM bans WHERE identifier='$playerLicense' AND (banned_until >= '$time' OR banned_until = 0)");

$banned_until = false;
foreach($result as $row)
{
	if($row['banned_until'] == 0) {
		$banned_until = "Permanent";
	} else {
		$banned_until = gmdate("d.m.Y h:i:s A", $row['banned_until']) . " GMT";
	}
	echo json_encode(array(
		"banned" => "true",
		"reason" => $row['reason'],
		"staff" => $row['staff_name'],
		"ban_issued" => gmdate("d.m.Y h:i:s A", $row['ban_issued']) . " GMT",
		"banned_until" => $banned_until,
	));
}

if($banned_until == false)
{
	echo json_encode(array(
		"banned" => "false",
	));
}
?>