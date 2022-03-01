<?php
require_once(__DIR__ . "/../config.php");

/**
 * Get a Discord User object
 *
 * @param string $id A user's Discord ID
 * @return object see Discord "User" documentation
 * @url https://discordapp.com/developers/docs/resources/user
 */
function getDiscordUser($id) {

    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/users/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $user = json_decode(curl_exec($ch));

    curl_close($ch);

    return $user;
}

/**
 * Get a Discord User's avatar URL
 *
 * @param string $id A user's Discord ID
 * @return string avatar URL
 * @url https://discordapp.com/developers/docs/reference#image-formatting
 */
function getDiscordAvatarByID($id, $resolution, $format) {

    if ($resolution % 16 != 0 || $resolution > 2048 || $resolution < 16) {
        throw new InvalidArgumentException('The resolution must be a power of two between 16 and 2048 inclusive.');
    }

    $user = getDiscordUser($id);
    $hash = $user->avatar;

    $gif = "a_";

    if(strpos($hash, $gif) !== false) {
        $format = "gif";
    } 
    else {
        $format = "jpg";
    }


    return "https://cdn.discordapp.com/avatars/" . $id . "/". $hash ."." . $format . "?size=" . $resolution;
}

/**
 * Get the guild object
 *
 * @return object see Discord "Guild" documentation
 * @url https://discordapp.com/developers/docs/resources/guild
 */
function getGuild() {
    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/guilds/' . GUILD_ID,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $guild = json_decode(curl_exec($ch));

    curl_close($ch);

    return $guild;
}

/**
 * Get a guild member object
 *
 * @param string $id A user's Discord ID
 * @return object see Discord "Guild Member" documentation
 * @url https://discordapp.com/developers/docs/resources/guild#guild-member-object
 */
function getGuildMember($id) {
    $ch = curl_init();

    curl_setopt_array($ch, array (
        CURLOPT_URL => 'https://discordapp.com/api/v6/guilds/' . GUILD_ID . '/members/' . $id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('.BASE_URL.', 1.0.0)',
        CURLOPT_HTTPHEADER => array('Authorization: Bot ' . TOKEN)
    ));

    $guildMember = json_decode(curl_exec($ch));

    curl_close($ch);

    return $guildMember;
}

/**
 * Get's a guild member's roles as an array
 *
 * @param string $id A user's Discord ID
 * @return string[] array of user's roles
 */
function getGuildMemberRoles($id) {
    $guildMember = getGuildMember($id);
    return $guildMember->roles;
}

/**
 * Get the permission level for a given user ID - if zero is returned the user is not staff.
 *
 * @param string $id A user's Discord ID
 * @return int permission level
 */
function checkDiscordPermissions($id) {
    global $PERMS;
    $roles = getGuildMemberRoles($id);

    foreach ($PERMS as $key => $val) {

        if (in_array($key, $roles)) {
            $_SESSION['permissionrole'] = $key;
            $_SESSION['permissionranks'] = $val;
            $_SESSION['logged_in'] = true;
        }

    }

}


/**
 * Sends a message to the server, accepts both richEmbed objects and plaintext strings
 *
 * @param mixed $content
 */
function sendLog($content, $channelid) {
    $ch = curl_init();
    $body = array();

    if (is_string($content)) {
        $body = array("content" => $content);
    } else {
        $body = array("embed" => $content);
    }

    curl_setopt_array($ch, array(
        CURLOPT_URL => "https://discord.com/api/v6/channels/".$channelid."/messages",
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($body),
        CURLOPT_HTTPHEADER => array(
            "Content-Type:application/json",
            "Authorization: Bot ". TOKEN .""
        ),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_USERAGENT => 'DiscordBot ('. BASE_URL .', 1.0.0)'
    ));

    $res = curl_exec($ch);
    curl_close($ch);
}


/**
 * Class richEmbed
 * @url https://discordapp.com/developers/docs/resources/channel#embed-object
 */
class richEmbed {
    private $title;
    private $description;
    private $fields;

    /**
     * richEmbed constructor.
     * @param $title
     * @param $content
     */
    function __construct( $title, $content ) {

        if (!is_string($title)) { throw new InvalidArgumentException("Title value must be a string"); }
        if (!is_string($content)) { throw new InvalidArgumentException("Title value must be a string"); }

        $this->title = $title;
        $this->description = $content;
        $this->fields = array();
    }

    /**
     * Adds a field to the array
     *
     * @param $title
     * @param $content
     * @param $inline
     */
    public function addField($title, $content, $inline) {
        if (is_bool($inline)) {
            array_push($this->fields, array(
                "name" => $title,
                "value" => $content,
                "inline" => $inline
            ));
        } else {
            throw new InvalidArgumentException("Inline must be a boolean value");
        }
    }

    /**
     * Builds array structure for sending
     *
     * @return array
     */
    public function build() {

        return array(
            "title" => $this->title,
            "description" => $this->description,
            "color" => hexdec(LOGS_COLOR),
            "fields" => $this->fields,
            "footer" => array("text" => "STAFF PANEL | By Hamz#0001", "icon_url" => "" . LOGS_IMAGE . "")
        );
    }
}
