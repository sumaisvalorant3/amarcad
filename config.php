<?php
// PANEL URL \\
define('BASE_URL', 'https://localhost/hamzpanel');


// SQL DATABASE CONNECTION \\
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'hamzpanel');


// DISCORD OAUTH2 \\
define('TOKEN', 'OTQzOTQ3NjQyNDI4MzM0MDgw.Yg6dvw.qOBIKvSHd6X-Q9-vGGH1ulj57yg');
define('GUILD_ID', '865525481355608065');
define('OAUTH2_CLIENT_ID', '943947642428334080');
define('OAUTH2_CLIENT_SECRET', '4vwkIWELI_bBWvZY0K56cT5-SQOlaBLO');


// DISCORD LOGS \\
define('LOGS_IMAGE', 'https://imgur.com/yaHpliD.png');
define('LOGS_COLOR', '#00B2FF');

// Channel ID's
define('WARN_LOGS', '943502423543148564');
define('KICK_LOGS', '943502423543148564');
define('BAN_LOGS', '943502423543148564');
define('COMMEND_LOGS', '943502423543148564');
define('NOTE_LOGS', '943502423543148564');
define('REMOVEBAN_LOGS', '943502423543148564');
define('REMOVEWARN_LOGS', '943502423543148564');
define('REMOVEKICK_LOGS', '943502423543148564');
define('REMOVECOMMEND_LOGS', '943502423543148564');
define('REMOVENOTE_LOGS', '943502423543148564');
define('STRIKE_LOGS', '943502423543148564');
define('REMOVESTRIKE_LOGS', '943502423543148564');
define('BROADCAST_LOGS', '943502423543148564');
define('LOGIN_LOGS', '943502423543148564');
define('REMOVEOLDNAME_LOGS', '943502423543148564');
define('REMOVESTAFF_LOGS', '943502423543148564');


// GENERAL \\
define('SERVER_NAME', 'SM Framework V1.0');
define('SECRET', 'test123');
define('STEAM_API', '6314C858844B6C544C1B591C061FE195'); // https://steamcommunity.com/dev/apikey
define('ACCENT_COLOR', '#00B2FF');
define('ENABLE_RESOURCES', true);
define('ENABLE_BROADCAST', true);

// IP Stuff
$SERVERS = array(
	[
		'server_name' => 'SM Framework V1.0',
		'server_ip' => '127.0.0.1',
		'server_port' => '30120',
		'server_rcon_pass' => 'rcon_pass_here'
	],
	// [
	// 	'server_name' => 'Server 2',
	// 	'server_ip' => '127.0.0.1',
	// 	'server_port' => '30121',
	// 	'server_rcon_pass' => 'hjlank'
	// ]
);

// Trustscore Values
define('WARN_SCORE', '4');
define('KICK_SCORE', '20');
define('BAN_SCORE', '12');
define('COMMEND_SCORE', '4');
define('PERMBAN_TRUSTSCORE', '0');
define('PERMBAN_TRUSTSCORE_MASSAGE', 'YOUR TRUSTSCORE IS LOWER THAN THIS SERVERS MINIMUM REQUIREMENT.');

// Document Links
$DOCS = [
	"Server Rules" => "https://docs.hamz.dev",
	"Server Penal Code" => "https://docs.hamz.dev",
];


// PERMISSIONS \\
$PERMS = [
	// Below are Default, Feel free to setup to your likings.

	// Moderator
    '214183846102302820' => [
        'AddNote',
        'RemoveNote',
        'AddWarn',
        'AddKick',
        'AddTempBan',
        'AddCommend',
        'RemoveCommend',
    ],
    // Staff
    '314183846102302820' => [
        'AddNote',
        'RemoveNote',
        'AddWarn',
        'RemoveWarn',
        'AddKick',
        'RemoveKick',
        'AddTempBan',
        'AddPermBan',
        'RemoveBan',
        'AddCommend',
        'RemoveCommend',
        'ViewOldNames',
        'RemoveOldNames',
        'Broadcast',
        'ViewStaffSection',
    ],
    // Admin
    '714183846102302820' => [
        'AddNote',
        'RemoveNote',
        'AddWarn',
        'RemoveWarn',
        'AddKick',
        'RemoveKick',
        'AddTempBan',
        'AddPermBan',
        'RemoveBan',
        'AddCommend',
        'RemoveCommend',
        'ViewOldNames',
        'RemoveOldNames',
        'Broadcast',
        'ViewStaffSection',
        'RemoveStrike',
        'RemoveStaff',
        'ManageServerResources',
    ],
];
?>