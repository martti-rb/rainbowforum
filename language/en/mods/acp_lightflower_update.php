<?php
/**
*
* acp_forums [English]
*
* @package language
* @version $Id$
* @copyright (c) 2005 phpBB Group / 2011 Zmarty
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/


if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine




$lang = array_merge($lang, array(
	
	
	'LIGHTFLOWER_UPDATE_SUCCESSFUL'	=> 'the lightflower update was succesful',
	'LIGHTFLOWER_UPDATE_COMPLETED'	=> 'lightflower update complete',	
	'UPDATE_USER_BIRTHDAYS'		=> 'update user birthdays in database (d,m,y)',
	'UPDATE_PREFIXES'			=> 'update to the new topic title prefix system.',
	'RANDOMIZE_TOPIC_COLORS'	=> 'randomize topic colors',
	'TOPIC_DARK_BG'				=> 'dark topic colors (when randomize topic colors)',
	'TOPIC_DARK_BG_RANDOM'		=> 'randomize  topic colors darkness (when randomize topic colors)',
	'RANDOMIZE_FORUM_COLORS'	=> 'randomize forum colors',
	'FORUM_COLOR_SELECT'		=> 'select forums to set color value below',
	'FORUM_COLOR_VALUE'			=> 'forum color value',
	'TOPIC_COLORS_MATCH'		=> 'match topic colors to forum colors',
	'UPDATE_CALENDAR'			=> 'update calendar colums in topic table.',
	'UPDATE_LOCATIONS'			=> 'update locations colums in topic table.',
	
	'SET_LOCATION_TAGS'			=> 'set location tags',
	'FORUM_SELECT'				=> 'select forum',
	'FORUM_SELECT_EXPLAIN'		=> '',
	'CONTINENT_SELECT'			=> 'select continent',
	'CONTINENT_SELECT_EXPLAIN'	=> '',
	'AUTO_LOCATION'				=> 'automatic location set',
	'AUTO_LOCATION_EXPLAIN'		=> 'search location in subject titles, only in above selected forum and subforums. skipped when the continent mismatches',
	'ERASE_LOCATION'			=> 'erase location',
	'ERASE_LOCATION_EXPLAIN'	=> 'erase the full location tag in topics in above selected forum and subforums first.',

	'PARENT_MISMATCH'			=> 'parent mismatch',
	'MULTI'						=> 'multi',
	'TOPIC_ID'					=> 'topic id',
	'ABORTED_TOPIC_TITLE'		=> 'operation aborted for these topics',
	'ARCHIVE_FORUM_DEST_SELECT'	=> 'destination archive forum tag',
	'ARCHIVE_FORUM_DEST_SELECT_EXPLAIN'	=> '',
	'ARCHIVE_FORUM_TAGS'		=> 'archive forum tags',
	'ARCHIVE_FORUM_SELECT'		=> 'select \'archived from\' forums',
	'ARCHIVE_FORUM_SELECT_EXPLAIN'	=> '',

	
));

?>