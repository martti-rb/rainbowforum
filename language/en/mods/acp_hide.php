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

	'HIDE_LEGEND'			=> 'Hide settings',
	
	'HIDE_ANNOUNCE_ENABLE'			=> 'Hide announcements enable',
	'HIDE_ANNOUNCE_EXPLAIN'			=> 'Makes it possible for users to close the announcement and global topics.',

	'HIDE_FILTERS_ENABLE'			=> 'Hide filters enable',
	'HIDE_FILTERS_EXPLAIN'			=> 'Makes it possible for users to close the filters.',


	
));

?>