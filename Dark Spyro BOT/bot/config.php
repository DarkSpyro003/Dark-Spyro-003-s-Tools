<?php
// ** Dark_Spyro_Bot Version Information ** //
/* Very brief description of bot for use in CTCP VERSION replies */
$BOT_DESCRIPTION = 'DS_Bot_Default';

// ** IRC Connection Information ** //
/* IRC Host to connect to */
$IRC_HOST = 'irc.freenode.net';

/* IRC Port to connect to $IRC_HOST with */
$IRC_PORT = '6667';

/* Public IRC nick for this bot */
$IRC_NICK = 'DS_Bot_Default';

/* Real name for this bot */
$IRC_REALNAME = 'DS_Bot_Default';

/* Ident/Username, this is does not have to be the same as the nick */
$IRC_USERNAME = 'DS_Bot_Default';

/* IRC Usermode, if you don't know what this is leave it set to 0 */
$IRC_USERMODE = '0';

/* IRC Services identification password */
$IRC_PASSWORD = 'password';

/* IRC Registration email */
$REG_EMAIL = 'something@something.com';

/* IRC Channels to join after connection to the $IRC_HOST */
/**
* This is a key => value pair array, where the key is the channel to join
* and the value is whether or not the nick is required as the first part
* the message.
** true: require the bots nick as first part of message
* false: do not require the bots nick
*/
$irc_channels = array(
'#mychannel' => false,
//'##arcemu' => false,
);

// ** Admin configuration ** //
/* Admin users allowed to bypass limits and run restricted commands */
$bot_admins = array(
'user' => 'MyAdmin@HostMask.example',
);

// ** Trac configuration ** //
/**
* Trac hosts to query for ticket information and changesets, do not include a trailing slash in the url
* Make sure to specify a key titled fallback, as a fallback for channels which are not defined below,
* this is useful for channels that you told the bot to join after it was started
** The url should be to base of your trac installation
* fallback trac should not have a # before it.
*/
$trac_hosts = array(
'#mychannel' => 'http://sometracker.default/',
'fallback'   => 'http://mymainproject.default/',
);

$gcommandprefix = '='; // please also put the same one in public $commandprefix

// MySQL configuration
$db_host = 'localhost';
$db_user = 'readonly_forbot';
$db_password = 'dbpassgoeshere';
$db_ = 'BOTDB';
$mail_db = 'BOTDB_MAIL';

?>