<?php
session_start();
require_once(__DIR__ . "/../config.php");
require_once(__DIR__ . "/discord_functions.php");

$USR_username; 
$USR_discrim; 
$USR_id; 
$USR_SERVER = false; 
$USR_guild_active = "false"; 
$redirect_uri = BASE_URL."/actions/register.php";
$DISCORD_LOGIN_URL = "https://discord.com/api/oauth2/authorize?client_id=".OAUTH2_CLIENT_ID."&redirect_uri=".BASE_URL."%2Factions%2Fregister.php&response_type=code&scope=identify%20guilds"; 

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (isset($_GET["error"])) {
        echo json_encode(array("message" => "Authorization Error"));
    } elseif (isset($_GET["code"])) {
		
		$data = array(
				"client_id" => OAUTH2_CLIENT_ID,
				"client_secret" => OAUTH2_CLIENT_SECRET,
				"grant_type" => "authorization_code",
				"code" => $_GET["code"],
				"redirect_uri" => $redirect_uri,
				"scope" => "identify guilds"
			);
			
			$token = curl_init();
			curl_setopt($token, CURLOPT_URL, "https://discord.com/api/oauth2/token");
			curl_setopt($token, CURLOPT_POST, 1);
			curl_setopt($token, CURLOPT_POSTFIELDS, http_build_query($data));		
			curl_setopt($token, CURLOPT_RETURNTRANSFER, true);
			$resp = json_decode(curl_exec($token));
			curl_close($token);
		
		// Get user object
        if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discord.com/api/users/@me";
			$headers = array("Authorization: Bearer {$access_token}");
			
			$info = curl_init();
			curl_setopt($info, CURLOPT_URL, $info_request);
			curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
			
            $user = json_decode(curl_exec($info));
            curl_close($info);
            $USR_username = $user->username;
			$USR_discrim = $user->discriminator;
			$USR_id = $user->id;

			checkDiscordPermissions($USR_id);

        } else {
            echo json_encode(array("message" => "Couldn't get user object!"));
        }
		
		// Get guilds object
		if (isset($resp->access_token)) {
            $access_token = $resp->access_token;
            $info_request = "https://discord.com/api/users/@me/guilds";
			$headers = array("Authorization: Bearer {$access_token}");
			
			$info = curl_init();
			curl_setopt($info, CURLOPT_URL, $info_request);
			curl_setopt($info, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($info, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($info, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($info, CURLOPT_VERBOSE, 1);
			curl_setopt($info, CURLOPT_SSL_VERIFYPEER, 0);
			
            $guilds = curl_exec($info);
            curl_close($info);
			
			// Convert JSON string to Array
			$NewArray = json_decode($guilds, true);
			foreach ($NewArray as $key => $value) {
				if($value["id"] == GUILD_ID)
				{
					$USR_SERVER = true;
					
				} else{}
			}
			
			if($USR_SERVER == false)
			{
				 header("Location: ../login.php?unauthorised");
			}
			else
			{
				try{
					$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
				} catch(PDOException $ex)
				{
					echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
				}
				
				$result = $pdo->query("SELECT steamid FROM users WHERE steamid='$USR_id'");
				$exisits = false;
				foreach($result as $row)
				{
					$exisits = true;					
					setupAndSendOnline();
				}
				
				if($exisits == false)
				{
					if ($_SESSION['logged_in'] != true)
					{
						$log = new richEmbed("UNAUTHORISED LOGIN ATTEMPT", "<@{$USR_id}> has just tried to log into the Panel!");
						$log = $log->build();
						sendLog($log, LOGIN_LOGS);
						header("Location: ../login.php?unauthorised");
					} else {
						$stmt = $pdo->prepare("INSERT INTO users (name, steamid, avatar) VALUES (?, ?, ?)");
						$result = $stmt->execute(array($USR_username, $USR_id, "TOMS AVATAR STRING"));
						setupAndSendOnline();
					}
				}
			}
			
        } else {
            echo json_encode(array("message" => "Couldn't get guilds object!"));
        }
		
		
    } else {
        header("Location:" . $DISCORD_LOGIN_URL);
    }
	
function setupAndSendOnline(){
	
	global $USR_id, $USR_username;
	
	$avatar = getDiscordAvatarByID($USR_id, "512", "gif");
	
	//ASSIGN THE SESSIONS
    $_SESSION['staffid'] = $USR_id;
    $_SESSION['staffname'] = $USR_username;
	$_SESSION['staffavatar'] = $avatar; 
	
	try{
		$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASSWORD);
	} catch(PDOException $ex)
	{
		echo json_encode(array("response" => "400", "message" => "Missing Parameters"));
	}
	
	// UPDATE THE PLAYERS AVATAR WITH TOMS AWESOME FUNCTION HERE
	$result = $pdo->query("UPDATE users SET name='$USR_username', avatar='$avatar' WHERE steamid='$USR_id'");

	if($_SESSION['logged_in'] != true)
	{
		$log = new richEmbed("UNAUTHORISED LOGIN ATTEMPT", "<@{$USR_id}> has just tried to log into the Panel!");
		$log = $log->build();
		sendLog($log, LOGIN_LOGS);
		header("Location: ../login.php?unauthorised");
	} else {
		// LOG LOGIN
		$log = new richEmbed("STAFF LOGGED IN", "<@{$USR_id}> just logged into the panel!");
		$log = $log->build();
		sendLog($log, LOGIN_LOGS);
		header("Location: ../index.php");
	}
}
	
?>

