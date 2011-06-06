<?php

require_once('config.php'); // _once is better than without for speed on windows

// The IRC class
require_once('../pear/SmartIRC.php');

// This opens /.svn/entries/ and reads lines 4 and 6, revision and svn URL.
$svn = File('.svn/entries');
$svnrev = $svn[3];
$svnaddress = $svn[5];
unset($svn);

// The bot class
class Dark_Spyro_Bot
{	private $lastactionforuser;
	// Should people be able to use this bot to send botmail?
	private $EnableBotAsMailer = true;
	// Command prefix
	public $commandprefix = '=';
	/* !!!!!!!!!!!!!!!!!!!!!!!!!!!!! *
	* !!         WARNING         !! *
	* !! Do not modify anything  !! *  => Unless you know what you're doing!
	* !!    beyond this point    !! *
	* !!!!!!!!!!!!!!!!!!!!!!!!!!!!! */
	private $botop = 'noname'; // Botop identifies with function identbotop
	// Constructor
	function Dark_Spyro_Bot()
	{
		global $db_host;
		global $db_user;
		global $db_password;
		global $db_;
		global $mail_db;
		$this->dbhost = $db_host;
		$this->dbuser = $db_user;
		$this->dbpassword = $db_password;
		$this->db = $db_;
		$this->maildb = $mail_db;
	}

	// Response to the help command
	function help(&$irc, &$data)
	{
		global $svnrev;
		global $svnaddress;
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Welcome to Dark_Spyro_Bot a php(CURL+SMARTIRC), Trac, light AI, trigger, and CIA.vc integrated bot! Developed by Dark_Spyro_003(Sparx) & Marforius.');
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Commands list available at: http://ds003.com/commandlist.html Revision' . $svnrev . '');
		$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Source code is available at ' . $svnaddress . '');
	}
	function umad(&$irc, &$data)
			{
				$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'HONK HONK!!!');
			}
	function commandhandler(&$irc, &$data)
	{
		$isHuman = CheckHuman($data->nick);
		if( $isHuman ) // Bots shouldn't talk to other bots. Add other bots to BOTDB.IrcBotList in SQL
		{
			$fargs = explode(' ', strtolower($data->message));
			$cargs = explode(' ', $data->message);
			if( $fargs[0] == $this->commandprefix.'disconnect' || $fargs[0] == $this->commandprefix.'quit' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					if( $fargs[1] != '' )
					{
						$arguments = "";
						$numargs = count($fargs);
						$f = 1;
						while ($f < $numargs)
						{
							if( $f != 1 ){$arguments = $arguments.' '; }
							$arguments = $arguments.$fargs[$f];
							$f++;
						}
						$irc->quit($arguments);
					}
					else
					{
						$irc->quit('Goodbye.');
					}
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'help' )
			{
				global $IRC_USERNAME;
				global $svnrev;
				global $svnaddress;
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Welcome to ' . $IRC_USERNAME . ': Dark_Spyro_Bot Rev.' . $svnrev . ', a php(CURL+SMARTIRC), Trac, light AI, trigger, and CIA.vc integrated bot! Developed by Dark_Spyro_003(Sparx) & Marforius.');
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Commands list available at: http://ds003.com/commandlist.html');
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Source code is available at ' . $svnaddress . ' GPLV3+, by-nc-sa');
			}
			elseif( $fargs[0] == $this->commandprefix.'nickregist' )
			{
				global $IRC_PASSWORD;
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$irc->message(SMARTIRC_TYPE_QUERY, 'NickServ', 'REGISTER', $IRC_PASSWORD, $REG_EMAIL);
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Registered the nickname with NickServ.');
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'nickident' )
			{
				global $IRC_PASSWORD;
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$irc->message(SMARTIRC_TYPE_QUERY, 'NickServ', 'IDENTIFY', $IRC_PASSWORD);
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Identified the nickname with NickServ.');
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'join' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$joinarray = array(0 => '');
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						$joinarray[$f-1] = $fargs[$f];
						$f++;
					}
					$irc->join($joinarray);
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'leave' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$leavearray = array(0 => '');
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						$leavearray[$f-1] = $fargs[$f];
						$f++;
					}
					$irc->part($leavearray);
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'npc' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, name from creature_names where entry = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/npc='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested NPC was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, name from creature_names where name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/npc='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested NPC was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'item' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, name1 from items where entry = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'name1');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/item='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested item was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, name1 from items where name1 like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'name1');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/item='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested item was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'sql query' && $fargs[1] != '' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = $arguments.' LIMIT 1';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested spell was not found. Are you sure it exists?');
					}
					else
					{
						$row = mysql_fetch_row($result);
						$finaloutput = '';
						$i = 0;
						while ($f < count($row))
						{
							if( $i != 0 ){$finaloutput = $finaloutput.', ';}
							$finaloutput = $finaloutput.$row[$i];
							$i++;
						}
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Results for your query are: '.$finaloutput);
					}
					mysql_close();
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'spell' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select Id, Name from spell where Id = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'Id');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/spell='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested spell was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select Id, Name from spell where Name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'Id');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/spell='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested spell was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'class' && $fargs[1] != '' && $fargs[3] == '' ) // There are classes with a space in the name, no check on args[2]
			{
				$class = "none";
				$classid = "0";
				if( $fargs[2] != '' )
				{
					$class = $fargs[1].' '.$fargs[2];
				}
				else
				{
					$class = $fargs[1];
				}
				switch($class)
				{
				case 'warrior':
					$classid = '1';
					break;
				case 'paladin':
					$classid = '2';
					break;
				case 'hunter':
					$classid = '3';
					break;
				case 'rogue':
					$classid = '4';
					break;
				case 'priest':
					$classid = '5';
					break;
				case 'death knight':
				case 'deathknight':
					$classid = '6';
					break;
				case 'shaman':
					$classid = '7';
					break;
				case 'mage':
					$classid = '8';
					break;
				case 'warlock':
					$classid = '9';
					break;
				case 'druid':
					$classid = '11';
					break;
				default:
					$classid = $fargs[1];
					break;
				}
				if( !is_numeric($classid) || $classid == 10 || $classid > 11 || $classid < 1 ) // No more non-existing classes
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Invalid class name or ID.');
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'http://www.wowhead.com/class='.$classid);
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'achievement' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select id, name from achievement where id = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'id');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/achievement='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested achievement was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select id, name from achievement where name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'id');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/achievement='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested achievement was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'faction' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from faction where ID = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/faction='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested faction was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from faction where Name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/faction='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested faction was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'object' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, Name from gameobject_names where entry = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $entry.' - '.$name);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested object was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select entry, Name from gameobject_names where Name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'entry');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $entry.' - '.$name);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested object was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'skill' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from skillline where ID = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/skill='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested skill was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from skillline where Name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/skill='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested skill was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'title' && $fargs[1] != '' && $fargs[2] == '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select id, name from CharTitles where id = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'id');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/title='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested title was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select id, name from CharTitles where name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'id');
						$name = mysql_result($result,$i,'name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/title='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested title was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'zone' && $fargs[1] != '' )
			{
				if( is_numeric($fargs[1]) )
				{
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from areatable where Id = '.$fargs[1].' limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/zone='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested zone was not found. Are you sure it exists?');
					}
					mysql_close();
				}
				else
				{
					$arguments = "";
					$numargs = count($fargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$fargs[$f];
						$f++;
					}
					mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
					mysql_select_db($this->db);
					$query = 'select ID, Name from areatable where Name like "%'.$arguments.'%" limit 3;';
					$result = mysql_query($query);
					$numrows = mysql_numrows($result);
					$i=0;
					while ($i < $numrows)
					{
						$entry = mysql_result($result,$i,'ID');
						$name = mysql_result($result,$i,'Name');
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $name.': http://www.wowhead.com/zone='.$entry);
						$i++;
					}
					if( $numrows == 0 || $numrows == '' )
					{
						$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested zone was not found. Are you sure it exists?');
					}
					mysql_close();
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'race' && $fargs[1] != '' && $fargs[3] == '' ) // There are races with a space in the name, no check on args[2]
			{
				$race = "none";
				$raceid = "0";
				if( $fargs[2] != '' )
				{
					$race = $fargs[1].' '.$fargs[2];
				}
				else
				{
					$race = $fargs[1];
				}
				switch($race)
				{
				case 'human':
					$raceid = '1';
					break;
				case 'orc':
					$raceid = '2';
					break;
				case 'dwarf':
					$raceid = '3';
					break;
				case 'night elf':
				case 'nightelf':
					$raceid = '4';
					break;
				case 'undead':
					$raceid = '5';
					break;
				case "tauren":
					$raceid = '6';
					break;
				case 'gnome':
					$raceid = '7';
					break;
				case 'troll':
					$raceid = '8';
					break;
				case 'bloodelf':
				case 'blood elf':
					$raceid = '10';
					break;
				case 'draenei':
					$raceid = '11';
					break;
				default:
					$raceid = $fargs[1];
					break;
				}
				if( !is_numeric($raceid) || $raceid == 9 || $raceid > 11 || $raceid < 1 ) // No more non-existing races
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'Invalid race name or ID.');
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'http://www.wowhead.com/race='.$raceid);
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'say' && $cargs[1] != '' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$arguments = "";
					$numargs = count($cargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$cargs[$f];
						$f++;
					}
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $arguments);
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( ($fargs[0] == $this->commandprefix.'action' || $fargs[0] == $this->commandprefix.'emote') && $cargs[1] != '' )
			{
				if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
				{
					$arguments = "";
					$numargs = count($cargs);
					$f = 1;
					while ($f < $numargs)
					{
						if( $f != 1 ){$arguments = $arguments.' '; }
						$arguments = $arguments.$cargs[$f];
						$f++;
					}
					$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, $arguments);
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
				}
			}
			elseif( (substr($fargs[0], 0, 2) == $this->commandprefix.$this->commandprefix || $fargs[0] == $this->commandprefix.'info') && $fargs[1] != '' && $fargs[3] == '' )
			{
				/* Types
				0 = regular text response
				1 = include something numeric*/
				$RequestedProject = strtolower(substr($fargs[0], 2));
				$RequestedTrigger = strtolower($fargs[1]);
				$RequestedNumeric = $fargs[2];
				mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
				mysql_select_db($this->db);
				$query = 'select * from projects';
				$result = mysql_query($query);
				$numrows = mysql_numrows($result);
				$CurrentProject = -1;
				$i=0;
				while ($i < $numrows)
				{
					$ID = mysql_result($result,$i,'ID');
					$ProjectTrigger = strtolower(mysql_result($result,$i,'ProjectTrigger'));
					if( $RequestedProject == $ProjectTrigger )
					{
						$CurrentProject = $ID;
					}
					$i++;
				}
				if( $numrows == 0 || $numrows == '' || $CurrentProject == -1 )
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested project does not exist.');
					mysql_close();
					return;
				}
				$query = 'select * from project_triggers where ProjectID='.$CurrentProject.' AND `Trigger`="'.$RequestedTrigger.'" LIMIT 1;';
				$result = mysql_query($query);
				$numrows = mysql_numrows($result);
				$Type = 0;
				$i=0;
				while ($i < $numrows)
				{
					$ID = mysql_result($result,$i,'ID');
					$ProjectID = strtolower(mysql_result($result,$i,'ProjectID'));
					$Trigger = strtolower(mysql_result($result,$i,'Trigger'));
					$Type = mysql_result($result,$i,'Type');
					$ResultID = mysql_result($result,$i,'ResultID');
					$i++;
				}
				if( $numrows == 0 || $numrows == '' )
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'The requested trigger does not exist.');
					mysql_close();
					return;
				}
				$query = 'select * from project_results WHERE ID = '.$ResultID.' LIMIT 1;';
				$result = mysql_query($query);
				$numrows = mysql_numrows($result);
				$ResultText = '';
				$FailResult = '';
				$i=0;
				while ($i < $numrows)
				{
					$ID = mysql_result($result,$i,'ID');
					$ResultText = mysql_result($result,$i,'Result');
					$FailResult = mysql_result($result,$i,'WrongTypeResult');
					$i++;
				}
				if( $numrows == 0 || $numrows == '' || $ResultText == '' )
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel, 'ERROR: The requested result is missing!');
					mysql_close();
					return;
				}
				mysql_close();
				if( $Type == 1 && is_numeric($RequestedNumeric) && $RequestedNumeric != '' ) // NUMERIC
				{
					$ResultText = $ResultText.$RequestedNumeric;
				}
				elseif( $Type == 1 ) // Should be numeric but is missing!
				{
					$ResultText = $FailResult;
				}
				else
				{
					$ResultText = $ResultText;
				}
				$ResultTextArr = explode('<br>', $ResultText);
				foreach ($ResultTextArr as $i => $value)
				{
					$irc->message(SMARTIRC_TYPE_CHANNEL, $data->channel,$ResultTextArr[$i]);
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'time' && $fargs[2] == '' )
			{
				if( $fargs[1] == 'pro' )
				{
					$irc->message( SMARTIRC_TYPE_CHANNEL, $data->channel, date('d F Y H:i:s - ').'GMT '.date('P') );
				}
				else
				{
					$irc->message( SMARTIRC_TYPE_CHANNEL, $data->channel, date('H:i:s - ').'GMT '.date('P') );
				}
			}
		}
	}
	function identbotop(&$irc, &$data)
	{
		global $IRC_PASSWORD;
		$fargs = explode(' ', $data->message);
		// Double check command parameters integrity
		if( strtolower($data->nick) != "nickserv" )
		{
			if( $fargs[0] == 'identify' && $fargs[1] != 'noname' && $fargs[2] == '' )
			{
				if( $fargs[1] == $IRC_PASSWORD )
				{
					$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'You have now been marked as the bot operator, '.$data->nick.'.');
					$this->botop = $data->from;
				}
				else
				{
					$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'The password is invalid!');
				}
			}
			else
			{
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Invalid amount of parameters for this command!');
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Usage: identify PASSWORD');
			}
		}
	}
	function mailhandler(&$irc, &$data)
	{
		if( $this->EnableBotAsMailer && strtolower($data->nick) != "nickserv" )
		{
			global $IRC_USERNAME;
			$fargs = explode(' ', strtolower($data->message));
			$cargs = explode(' ', $data->message);
			if( $fargs[0] == $this->commandprefix.'mailsend' && $cargs[1] != '' && $cargs[2] != '' )
			{
				$arguments = "";
				$numargs = count($cargs);
				$f = 2;
				while ($f < $numargs)
				{
					if( $f != 2 ){$arguments = $arguments.' '; }
					$arguments = $arguments.$cargs[$f];
					$f++;
				}
				mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
				mysql_select_db($this->maildb);
				if (strtolower($cargs[1]) != "nickserv" && strtolower($cargs[1]) != $IRC_USERNAME) // Do not send botmail to nickserv or the bot itself
				{
					$query = "insert into mail (sender, receiver, message) values ('".$data->nick."', '".$cargs[1]."', '".$arguments."');";
					mysql_query($query);
					mysql_close();
					$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Sent botmail successfully.');
					$irc->message(SMARTIRC_TYPE_QUERY, $fargs[1], 'You have new botmail.');
				}
			}
			elseif( $fargs[0] == $this->commandprefix.'mailcheck' )
			{
				mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
				mysql_select_db($this->maildb);
				$query = "select * from mail where receiver like '".$data->nick."' ORDER BY id ASC LIMIT 5;";
				$result = mysql_query($query);
				$numrows = mysql_numrows($result);
				$i=0;
				while ($i < $numrows)
				{
					$sender = mysql_result($result,$i,'sender');
					$receiver = mysql_result($result,$i,'receiver');
					$message = mysql_result($result,$i,'message');
					$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $sender.' mailed you: '.$message);
					$i++;
				}
				if( $numrows == 0 || $numrows == '' )
				{
					$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'You have no botmail.');
				}
				mysql_close();
			}
			elseif( $fargs[0] == $this->commandprefix.'maildelete' )
			{
				mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
				mysql_select_db($this->maildb);
				$query = "delete from mail where receiver like '".$data->nick."' limit 5;";
				mysql_query($query);
				mysql_close();
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'Deleted your botmail.');
			}
		}
	}
	function mailcheck(&$irc, &$data)
	{
		if( $this->EnableBotAsMailer && strtolower($data->nick) != "nickserv")
		{
			mysql_connect($this->dbhost, $this->dbuser, $this->dbpassword);
			mysql_select_db($this->maildb);
			$query = "select * from mail where receiver like '".$data->nick."' ORDER BY id ASC LIMIT 5;";
			$result = mysql_query($query);
			$numrows = mysql_numrows($result);
			if( $numrows > 0 )
			{
				$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, 'You have new botmail.');
			}
			mysql_close();
		}
	}
	function rejoin(&$irc, &$data)
	{
		$irc->join($data->channel);
	}
	function ActionFun(&$irc, &$data)
	{
		$human = CheckHuman($data->nick);
		if( $human && (time() - 15) > $this->lastactionforuser[$data->from] )
		{
			$this->lastactionforuser[$data->from] = time();
			$message = 'CRITICAL_ERROR';
			$random = rand(0, 21);
			switch($random)
			{
			case 0:
				$message = 'kills '.$data->nick.'.';
				break;
			case 1:
				$message = 'slaps '.$data->nick.'.';
				break;
			case 2:
				$message = 'murders '.$data->nick.'.';
				break;
			case 3:
				$message = 'burns '.$data->nick.'.';
				break;
			case 4:
				$message = 'sees '.$data->nick.'.';
				break;
			case 5:
				$message = 'looks at '.$data->nick.'.';
				break;
			case 6:
				$message = 'tickles '.$data->nick.'.';
				break;
			case 7:
				$message = 'slaps '.$data->nick.'.';
				break;
			case 8:
				$message = 'dances with '.$data->nick.'.';
				break;
			case 9:
				$message = 'squashes '.$data->nick.'.';
				break;
			case 10:
				$message = 'listens to '.$data->nick.'.';
				break;
			case 11:
				$message = 'loves '.$data->nick.'.';
				break;
			case 12:
				$message = 'kisses '.$data->nick.'.';
				break;
			case 13:
				$message = 'cuddles '.$data->nick.'.';
				break;
			case 14:
				$message = 'shoots '.$data->nick.'.';
				break;
			case 15:
				$message = 'robs '.$data->nick.'.';
				break;
			case 16:
				$message = 'kidnaps '.$data->nick.'.';
				break;
			case 17:
				$message = 'reprograms '.$data->nick.'.';
				break;
			case 18:
				$message = 'worships '.$data->nick.'.';
				break;
			case 19:
				$message = 'prays for '.$data->nick.'.';
				break;
			case 20:
				$message = 'eats '.$data->nick.'.';
				break;
			case 21:
				$message = 'clones '.$data->nick.'.';
				break;
			}
			$irc->message(SMARTIRC_TYPE_ACTION, $data->channel, $message);
		}
	}
	function identnickserv(&$irc)
	{
		global $IRC_PASSWORD;
		global $identwithnickservid;
		$irc->unregisterTimeid($identwithnickservid);
		$irc->message(SMARTIRC_TYPE_QUERY, 'NickServ', 'IDENTIFY', $IRC_PASSWORD);
	}
	function joinchann(&$irc, &$data)
	{
		$fargs = explode(' ', strtolower($data->message));
		$cargs = explode(' ', $data->message);
		if( $data->from == $this->botop && $data->from != '' && $data->from != 'noname' )
		{
			$joinarray = array(0 => '');
			$numargs = count($fargs);
			$f = 1;
			while ($f < $numargs)
			{
				$joinarray[$f-1] = $fargs[$f];
				$f++;
			}
			$irc->join($joinarray);
		}
		else
		{
			$irc->message(SMARTIRC_TYPE_QUERY, $data->nick, $data->nick.', you are not identified as a bot operator. (Access to this command was denied)');
		}
	}
}

/**
* Function to split a CSV string that includes enclosures
** PHP versions below 5.3 don't have good support for splitting CSV
* data as a string when encapsulated.	This is a very hacky way to
* split it while supporting enclosures, but seems to work very well.
** This function requires that there the CSV data is a single row and
* that enclosures are defined by a the " character.
*/
function split_csv( $csv ) {
	// Do a normal split based on commas
	$items = split( ',', $csv );
	// Loop through looking for the default enclosure character " and rebuild data inside of enclosures
	foreach ( $items as $item ) {
		if ( preg_match( '/^"/', $item ) && ! preg_match( '/^""/', $item ) )
		$start = true;
		if ( isset( $start ) && $start == true ) {
			if ( isset( $whole ) && strlen( $whole ) > 0 )
			$whole .= ',' . $item;
			else
			$whole = $item;
			if ( preg_match( '/"$/', $item ) && ! preg_match( '/""$/', $item ) ) {
				$start = false;
				$new_items[] = trim( rtrim( $whole ) );
				unset( $whole );
			}
		} else {
			$new_items[] = trim( rtrim( $item ) );
		}
	}
	return $new_items;
}
class trac
{	function __construct()
	{
		global $trac_hosts;
		$this->base_trac_url = $trac_hosts;
		global $db_host;
		global $db_user;
		global $db_password;
		global $db_;
		global $mail_db;
		$this->dbhost = $db_host;
		$this->dbuser = $db_user;
		$this->dbpassword = $db_password;
		$this->db = $db_;
		$this->maildb = $mail_db;
	}
	function http( $url, $method = 'get' )
	{
		if ( strtolower( $method ) == 'head' )
		$url = preg_replace( '/#.*$/', '', $url );
		$session = curl_init( $url );
		curl_setopt( $session, CURLOPT_HEADER, false );
		curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $session, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $session, CURLOPT_MAXREDIRS, 5 );
		curl_setopt( $session, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
		curl_setopt( $session, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $session, CURLOPT_SSL_VERIFYPEER, false );
		if ( strtolower( $method ) == 'head' )
		curl_setopt( $session, CURLOPT_NOBODY, true );
		$response['body'] = curl_exec( $session );
		$response['code'] = curl_getinfo( $session, CURLINFO_HTTP_CODE );
		curl_close( $session );
		return $response;
	}
	function ticket( &$irc, &$data )
	{
		global $irc_channels;
		$pre_pattern = ( $irc_channels[$data->channel] === true ) ? '(^' . $IRC_NICK . '.+\ )' : '(\ |^)';
		$pattern = "/($pre_pattern#|\/ticket\/)(\d{1,6})(\b|$)/iS";
		preg_match_all( $pattern, $data->message, $tickets, PREG_SET_ORDER );
		$human = CheckHuman($data->nick);
		if ( $human && is_array( $tickets ) && ! empty( $tickets ) )
		{
			// Build an array of only the ticket numbers, an array that does not include other matched text
			foreach ( $tickets as $ticket )
			{
				$ticket_numbers[] = $ticket[3];
			}
			// Make sure we don't have duplicate ticket numbers, we don't need to echo the same thing multiple times
			$ticket_numbers = array_unique( $ticket_numbers );
			foreach ( $ticket_numbers as $ticket_number ) {
				$base_trac_url = isset( $this->base_trac_url[$data->channel] ) ? $this->base_trac_url[$data->channel] : $this->base_trac_url['fallback'];
				$url = $base_trac_url . '/ticket/' . $ticket_number . '?format=csv';
				$response = $this->http( $url );
				// Make sure that the ticket exists and we get data back before trying to parse it
				if ( $response['code'] == 200 ) {
					// The CSV returned from trac returns a title row and the data, the split_csv function only
					// operates on a single row, so split the CSV at the line break and operate on each row individually
					list( $keys_string, $values_string ) = preg_split( '/\n/', $response['body'], 2 );
					$keys_array = split_csv( $keys_string );
					$values_array = split_csv( $values_string );
					/**
					* Merge the titles and data into a single array, with the title as the key and the data as the value
					*
					* The str_replace is to replace enclosures with something more reasonable when the enclosed data
					* has quotes too.
					*/
					$length = count( $keys_array );
					for ( $i = 0; $i < $length; $i++ ) {
						$ticket_data[$keys_array[$i]] = str_replace( array( '"""', '""' ), array( '"\'', '\'' ), $values_array[$i] );
					}
					// Build the output string
					if ( empty( $ticket_data['owner'] ) )
					$ticket_data['owner'] = '(no owner)';
					$output = $data->nick . ': ' . $base_trac_url . '/ticket/' . $ticket_number . ' ';
					$severity = empty( $ticket_data['severity'] ) ? '' : $ticket_data['severity'];
					if ( 'normal' == $severity )
					$severity = '';
					$priority = 'normal' == $ticket_data['priority'] ? '' : $ticket_data['priority'];
					if ( $severity || $priority )
					$output .= $severity . ( $severity && $priority ? '/' : '' ) . $priority . ', ';
					if ( empty( $ticket_data['milestone'] ) )
					$output .= 'Unassigned';
					else
					$output .= $ticket_data['milestone'];
					$output .= ', ' . $ticket_data['reporter'];
					$output .= '->' . $ticket_data['owner'];
					$output .= ', ' . $ticket_data['status'];
					$output .= ', ' . $ticket_data['summary'];
				} else {
					$output = $data->nick . ": Ticket does not exist";
				}
				// echo the above output string
				$irc->message( SMARTIRC_TYPE_CHANNEL, $data->channel, $output );
			}
		}
	}
	function changeset( &$irc, &$data )
	{
		global $irc_channels;
		$human = CheckHuman($data->nick);
		$pre_pattern = ( $irc_channels[$data->channel] === true ) ? '(^' . $IRC_NICK . '.+\ )' : '(\ |^)';
		// The ^B characters specify bold, the CIA bots tend to put the revisions in bold
		$pattern = "/{$pre_pattern}r?(\d{1,6}|head)?(\b|$)/iS";
		preg_match_all( $pattern, $data->message, $revisions, PREG_SET_ORDER );
		if ( $human && is_array( $revisions ) && ! empty( $revisions ) ) {
			// Build an array of only revision numbers, an array without any of the other matched text
			foreach ( $revisions as $revision ) {
				$revision_numbers[] = $revision[2];
			}
			// Make sure we don't have duplicate revisions numbers, we don't need to echo the same thing multiple times
			$revision_numbers = array_unique( $revision_numbers );
			foreach ( $revision_numbers as $revision_number ) {
				if ( is_string( $revision_number ) && strtolower( $revision_number ) == 'head' )
				$is_head = true;
				else
				$is_head = false;
				$base_trac_url = isset( $this->base_trac_url[$data->channel] ) ? $this->base_trac_url[$data->channel] : $this->base_trac_url['fallback'];
				if ( $is_head )
				$url = $base_trac_url . '/changeset/head';
				else
				$url = $base_trac_url . '/changeset/' . $revision_number;
				// Let's make sure the changeset exists before echoing a url
				$response = $this->http( $url, 'head' );
				if ( $response['code'] == 200 ) {
					if ( ! empty( $response['body'] ) ) {
						preg_match_all( '%<link>([^<]+)</link>%', $response['body'], $links, PREG_SET_ORDER );
						$url = $links[2][1];
					}
					$output = $data->nick . ": $url";
				} else {
					$output = $data->nick . ": Changeset does not exist";
				}
				// echo the url of the changeset
				$irc->message( SMARTIRC_TYPE_CHANNEL, $data->channel, $output );
			}
		}
	}
	function changesetrev( &$irc, &$data )
	{
		global $irc_channels;
		$human = CheckHuman($data->nick);
		$pre_pattern = ( $irc_channels[$data->channel] === true ) ? '(^' . $IRC_NICK . '.+\ )' : '(\ |^)';
		// The ^B characters specify bold, the CIA bots tend to put the revisions in bold
		$pattern = "/{$pre_pattern}rev?(\d{1,6}|head)?(\b|$)/iS";
		preg_match_all( $pattern, $data->message, $revisions, PREG_SET_ORDER );
		if ( $human && is_array( $revisions ) && ! empty( $revisions ) ) {
			// Build an array of only revision numbers, an array without any of the other matched text
			foreach ( $revisions as $revision ) {
				$revision_numbers[] = $revision[2];
			}
			// Make sure we don't have duplicate revisions numbers, we don't need to echo the same thing multiple times
			$revision_numbers = array_unique( $revision_numbers );
			foreach ( $revision_numbers as $revision_number ) {
				if ( is_string( $revision_number ) && strtolower( $revision_number ) == 'head' )
				$is_head = true;
				else
				$is_head = false;
				$base_trac_url = isset( $this->base_trac_url[$data->channel] ) ? $this->base_trac_url[$data->channel] : $this->base_trac_url['fallback'];
				 if ( $is_head )
				$url = $base_trac_url . '/changeset/head';
				else
				$url = $base_trac_url . '/changeset/' . $revision_number;
				// Let's make sure the changeset exists before echoing a url
				$response = $this->http( $url, 'head' );
				if ( $response['code'] == 200 ) {
					if ( ! empty( $response['body'] ) ) {
						preg_match_all( '%<link>([^<]+)</link>%', $response['body'], $links, PREG_SET_ORDER );
						$url = $links[2][1];
					}
					$output = $data->nick . ": $url";
				} else {
					$output = $data->nick . ": Changeset does not exist";
				}
				// echo the url of the changeset
				$irc->message( SMARTIRC_TYPE_CHANNEL, $data->channel, $output );
			}
		}
	}
}
function CheckHuman($nickname)
{
	global $db_host;
	global $db_user;
	global $db_password;
	global $db_;
	$human = true;
	mysql_connect($db_host, $db_user, $db_password);
	mysql_select_db($db_);
	$query = 'select id, name from IrcBotList where name like "'.strtolower($nickname).'";';
	$result = mysql_query($query);
	$numrows = mysql_numrows($result);
	$i=0;
	while ($i < $numrows)
	{
		$entry = mysql_result($result,$i,'id');
		$name = mysql_result($result,$i,'name');
		$human = false;
		$i++;
	}
	mysql_close();
	return $human;
}
// Please note, try out the /S option of regex, im not sure if it matters much, even if it makes a difference. Taking a bit more time to "study" the
// string before it gets matched, or "caching" it as I understand it may help the preformance of the bot - as it watches chat for near everything typed
// its now looking at all of the regex before reading them and encountering matches or things not matches; get the idea?
$bot = &new Dark_Spyro_Bot();
$trac = &new trac();
$irc = &new Net_SmartIRC();
$irc->setAutoReconnect( true );
$irc->setAutoRetry( true );
$irc->setUseSockets( true );
$irc->setCtcpVersion( $BOT_DESCRIPTION . '-R.' . $svnrev ); //Ex: "DS00B-R.10"
$irc->setChannelSyncing( true );
$irc->registerActionhandler( SMARTIRC_TYPE_CHANNEL, '/(\ |^)r?(\d{1,6}|head)?(\b|$)/iS', $trac, 'changeset' );
$irc->registerActionhandler( SMARTIRC_TYPE_CHANNEL, '/(\ |^)rev?(\d{1,6}|head)?(\b|$)/iS', $trac, 'changesetrev' );
$irc->registerActionhandler( SMARTIRC_TYPE_CHANNEL, '/((\ |^)#|\/ticket\/)([1-9][0-9]*)(\b|$)/iS', $trac, 'ticket' );
$irc->registerActionhandler( SMARTIRC_TYPE_QUERY, '/(/^part\ #.+)/iS', $admin, 'part' );
$irc->registerActionhandler( SMARTIRC_TYPE_QUERY, '/(^quit$)/iS', $admin, 'quit' );
$irc->registerActionhandler( SMARTIRC_TYPE_QUERY, '/(^nick\ .+)/iS', $admin, 'nick' );
$irc->registerActionhandler(SMARTIRC_TYPE_QUERY|SMARTIRC_TYPE_NOTICE, '/(help.*)/iS', $bot, 'help');
$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, '/(umad.*|yusomad.*|somadbreh.*|foamin.*|ragin.*)/iS', $bot, 'umad');
$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, $gcommandprefix.'/(.*)/iS', $bot, 'commandhandler');
$irc->registerActionhandler(SMARTIRC_TYPE_QUERY|SMARTIRC_TYPE_NOTICE, '/(join*)/iS', $bot, 'joinchann');
$irc->registerActionhandler(SMARTIRC_TYPE_CHANNEL, $gcommandprefix.'/(mail*)/iS', $bot, 'mailhandler');
$irc->registerActionhandler(SMARTIRC_TYPE_QUERY|SMARTIRC_TYPE_NOTICE, '/(identify.*)/iS', $bot, 'identbotop');
$irc->registerActionhandler(SMARTIRC_TYPE_ACTION, $IRC_NICK, $bot, 'ActionFun');
$irc->registerActionhandler(SMARTIRC_TYPE_JOIN, '/(.*)/iS', $bot, 'mailcheck');
$irc->registerActionhandler(SMARTIRC_TYPE_KICK, '', $bot, 'rejoin');
$identwithnickservid = $irc->registerTimeHandler(5000, $bot, 'identnickserv');
$irc->connect( $IRC_HOST, $IRC_PORT );
$irc->login( $IRC_NICK, $IRC_REALNAME, $IRC_USERMODE, $IRC_USERNAME, $IRC_PASSWORD );
$irc->join( array_keys( $irc_channels ) );
$irc->listen();
$irc->disconnect();
?>