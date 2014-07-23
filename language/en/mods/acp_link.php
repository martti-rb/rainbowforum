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

	'LINK_GENERAL'				=> 'general',
	'LINK_FORUM_ID'				=> 'link forum id',
	'LINK_FORUM_ID_EXPLAIN'		=> 'the id of the forum which will contain the links',
	'LINKS_PER_POST'			=> 'max. number of links per post',
	'LINKS_PER_POST_EXPLAIN'	=> '',
		
	'LINK_SOURCES'					=> 'link sources',
	'NEW_LINK_SOURCE'				=> 'new link source',
	'NEW_LINK_SOURCE_EXPLAIN'		=> '',
	'LINK_SOURCE_DELETE'			=> 'delete link sources',
	'LINK_SOURCE_DELETE_EXPLAIN'	=> '',
	'LINK_SOURCE_MOVE'				=> 'move deleted link source to',
	'LINK_SOURCE_MOVE_EXPLAIN'		=> '',

	'LINK_TYPES'				=> 'link types',
	'NEW_LINK_TYPE'				=> 'new link type',
	'NEW_LINK_TYPE_EXPLAIN'		=> '',
	'LINK_TYPE_DELETE'			=> 'delete link types',
	'LINK_TYPE_DELETE_EXPLAIN'	=> '',
	'LINK_TYPE_MOVE'			=> 'move deleted link type to',
	'LINK_TYPE_MOVE_EXPLAIN'	=> '',	
	
	
	
	
	'LINK_SETTINGS_UPDATE_SUCCESSFUL' 	=> 'The link settings update was successful.',

	
));

?>