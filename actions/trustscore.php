<?php
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/q3query.class.php");
require_once(__DIR__ . "/discord_functions.php");

try{
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
} catch(PDOException $ex)
{
    echo "Could not connect -> ".$ex->getMessage();
    die();
}

/**
 * @param string $id
 * @return string $trustScore
 */
function getTrustScore($id) {
    global $pdo;
    $trustScore = "";
    $result = $pdo->query("SELECT trustscore FROM players WHERE ID='$id'");
    foreach($result as $row)
    {
        $trustScore = $row['trustscore'];
    }
    return $trustScore;
}

/**
 * @param string $id
 * @param string $newTrustScore
 */
function setTrustScore($id, $newTrustScore) {
    global $pdo;
    $update = $pdo->query("UPDATE players SET trustscore='$newTrustScore' WHERE ID='$id'");
}

/**
 * @param string $id
 * @param string $type
 */
function updateTrustScore($type, $id) {
    $coefficients = array(
        "warn" => WARN_SCORE,
        "kick" => KICK_SCORE,
        "ban" => BAN_SCORE,
        "commend" => COMMEND_SCORE
    );

    $trustScore = getTrustScore($id);
    floatval($trustScore);

    switch($type) {
        case "warn":
            $trustScore = $trustScore - $coefficients["warn"];
            break;
        case "kick";
            $trustScore = $trustScore - $coefficients["kick"];
            break;
        case "ban":
            $trustScore = $trustScore - $coefficients["ban"];
            break;
        case "commend":
            $trustScore = $trustScore + $coefficients["commend"];
            break;
        case "unwarn":
            $trustScore = $trustScore + $coefficients["warn"];
            break;
        case "unkick":
            $trustScore = $trustScore + $coefficients["kick"];
            break;
        case "unban":
            $trustScore = $trustScore + $coefficients["ban"];
            break;
        case "uncommend":
            $trustScore = $trustScore - $coefficients["commend"];
            break;
        default:
            throw new InvalidArgumentException("Invalid trust score action");
            break;
    }
	
	if($trustScore > 100){
		$trustScore = 100;
	}
	
	$RoundedScore = strval(round($trustScore, 2));
    setTrustScore($id, $RoundedScore);
    if ($RoundedScore <= PERMBAN_TRUSTSCORE)
    {
        permBanPlayer($id);
    }
}

function permBanPlayer($id)
{
    global $pdo, $SERVERS;

    $time = gmmktime() + 3600;

    $reason = PERMBAN_TRUSTSCORE_MASSAGE;

    $result = $pdo->query("SELECT * FROM players WHERE ID='$id'");
    foreach ($result as $row)
    {
        $currentPlayerName = $row['name'];
        $currentPlayerLicense = $row['license'];
    }

    $stmt = $pdo->prepare("INSERT INTO bans (name, identifier, reason, staff_name, staff_steamid, ban_issued, banned_until) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $result2 = $stmt->execute(array($currentPlayerName, $currentPlayerLicense, $reason, "Panel Ban", "N/A", $time, "0"));

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
        
        if ($gameID != false)
        {
            $con = new q3query($server_ip, $server_port, $success);
            if (!$success) {
                die ("Something has gone wrong.");
            }
            $con->setServerPort($server_port);
            $con->setRconpassword($server_rcon_pass);
            $con->rcon("clientkick " . $gameID . " Ban reason: " . $reason);
            
            $con->rcon("say ^2" . $playersName . " ^0has been auto ^1banned ^0for ^3" . $reason);
        }

    }
    
    $log = new richEmbed("NEW BAN", "**{$playersName}** has been banned for ` {$reason} `");
    $log = $log->build();
    sendLog($log, BAN_LOGS);
}
?>