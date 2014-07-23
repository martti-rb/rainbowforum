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

	'FORUMS_SELECT_LEGEND'		=> 'Forums select',
	'FORUMS_SELECT'				=> 'Select the forums',
	'FORUMS_SELECT_EXPLAIN'		=> '',
	
	'PREFIX_GROUP'				=> 'Prefix group select',
	'PREFIX_GROUP_EXPLAIN'		=> '',
	
	'NO_PREFIX_GROUP'			=> '~',
	
	'PREFIXES'					=> 'prefixes',
	'NEW_PREFIX'				=> 'new prefix',
	'NEW_PREFIX_EXPLAIN'		=> '',
	'PREFIX_DELETE'				=> 'delete prefixes',
	'PREFIX_DELETE_EXPLAIN'		=> '',
	'PREFIX_MOVE'				=> 'move prefix ref to',
	'PREFIX_MOVE_EXPLAIN'		=> '',	
	
	'GROUPS'					=> 'groups',
	'NEW_GROUP'					=> 'new group',
	'NEW_GROUP_EXPLAIN'			=> '',
	'GROUP_DELETE'				=> 'delete groups',
	'GROUP_DELETE_EXPLAIN'		=> '',
	'GROUP_MOVE'				=> 'move group ref to',
	'GROUP_MOVE_EXPLAIN'		=> '',

	'PREFIXES_GROUPS'			=> 'prefixes in groups',
	'GROUP_SELECT'				=> 'select group',
	'GROUP_SELECT_EXPLAIN'		=> '',
	'PREFIX_ADD'				=> 'add prefixes',
	'PREFIX_ADD_EXPLAIN'		=> '',
	'PREFIX_REMOVE'				=> 'remove prefixes',
	'PREFIX_REMOVE_EXPLAIN'		=> '',	

	'CLEANUP_EMPTY_PREFIX'		=> 'cleanup empty prefix ids in topics',
	'CLEANUP_EMPTY_PREFIX_EXPLAIN'	=> '',
));

?>