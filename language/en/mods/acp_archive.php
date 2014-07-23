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

	'ARCHIVE_SETTINGS_UPDATE_SUCCESSFUL'	=> 'The update of the archive settings was successful.',
	'ARCHIVE_GENERAL'						=> 'general',
	'ARCHIVE_ID'							=> 'Archive id',
	'ARCHIVE_ID_EXPLAIN'					=> 'The id of the forum which will be the archive.',
	'AUTO_ARCHIVE'							=> 'auto archive',
	'AUTO_ARCHIVE_ENABLE'					=> 'Auto archive enable',
	'AUTO_ARCHIVE_ENABLE_EXPLAIN'			=> 'Activate automatic archiving of topics of which the calendar period is expired. Only topics with calendar periods attached are auto-archived.',
	'FORUMS_SELECT'							=> 'Edit auto archive per forum',
	'FORUMS_SELECT_EXPLAIN'					=> 'You can select more than one forum.',
	'AUTO_ARCHIVE_FORUM_ENABLE'				=> 'Auto archive enable in the selected forums',
	'AUTO_ARCHIVE_AFTER_DAYS'				=> 'Days after the calendar period in the selected forums',
	'AUTO_ARCHIVE_AFTER_DAYS_EXPLAIN'		=> 'A topic in the selected forum will be archived after this number of days after the end date of the last calendar period which is attached to the topic.',
	'AUTO_ARCHIVE_CRON'						=> 'Auto archive cron task.',
	'AUTO_ARCHIVE_EDIT_TIME'				=> 'Time in seconds from last edit before auto archive',
	'AUTO_ARCHIVE_EDIT_TIME_EXPLAIN'		=> 'This will prevent topics from instantly disappearing in the archive when a past calendar period is submitted.',
	'AUTO_ARCHIVE_CRON_TIME'				=> 'Cron timing in seconds',
	'AUTO_ARCHIVE_CRON_TIME_EXPLAIN'		=> 'This is the period after which each time topics are checked for auto-archiving. Set 86400 for one day', 

));

?>