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
	'ACP_TOPIC_COLOR_SETTINGS'					=> 'topic color settings',
	
	'TOPIC_COLORS_SETTINGS_UPDATE_SUCCESSFUL'	=> 'the update of the topic color settings was successful.',
	'TOPIC_COLOR_GENERAL'						=> 'general',	
	'TOPIC_COLOR_ENABLE'						=> 'enable topic colors',
	'TOPIC_COLOR_INPUT'							=> 'enable topic color input by users',
	
	'COLOR_SETS'					=> 'color sets',
	'NEW_COLOR_SET'					=> 'create new color set',
	'NEW_COLOR_SET_EXPLAIN'			=> 'write a name for the new color set.',
	'COLOR_SET_COPY'				=> 'copy the colors from',
	'COLOR_SET_COPY_EXPLAIN'		=> '',
	
	'COLOR_SET_DELETE'				=> 'select color sets to delete.',
	'COLOR_SET_DELETE_EXPLAIN'		=> '',
	
	'COLOR_SET_ASSIGN'				=> 'assign color sets',
	'COLOR_TOPIC_A'					=> 'topic a',
	'COLOR_TOPIC_A_EXPLAIN'			=> '',	
	'COLOR_TOPIC_B'					=> 'topic b',
	'COLOR_TOPIC_B_EXPLAIN'			=> '',		
	'COLOR_TOPIC_C'					=> 'topic c',
	'COLOR_TOPIC_C_EXPLAIN'			=> '',	
	'COLOR_TOPIC_D'					=> 'topic d',
	'COLOR_TOPIC_D_EXPLAIN'			=> '',	
	'QUOTE_A'						=> 'quote a',
	'QUOTE_A_EXPLAIN'				=> '',	
	'QUOTE_B'						=> 'quote b',
	'QUOTE_B_EXPLAIN'				=> '',	
	'QUOTE_C'						=> 'quote c',
	'QUOTE_C_EXPLAIN'				=> '',	
	'QUOTE_D'						=> 'quote d',
	'QUOTE_D_EXPLAIN'				=> '',
	'CODE_A'						=> 'code a',
	'CODE_A_EXPLAIN'				=> '',
	'CODE_B'						=> 'code b',
	'CODE_B_EXPLAIN'				=> '',
	'ATTACHMENT_A'					=> 'attachment a',
	'ATTACHMENT_A_EXPLAIN'			=> '',	
	'ATTACHMENT_B'					=> 'attachment b',
	'ATTACHMENT_B_EXPLAIN'			=> '',
	'FORUM_A'						=> 'forum a',
	'FORUM_A_EXPLAIN'				=> '',	
	'FORUM_B'						=> 'forum b',
	'FORUM_B_EXPLAIN'				=> '',		
	
	'MERGE_AMOUNT'					=> 'merge amount',
	'COLOR_SET_EDIT'				=> 'color set edit',
	'COLOR_SET_MERGE_FROM'			=> 'merge from',
	'MIN'							=> 'min',
	'MAX'							=> 'max',
	'UNDO'							=> 'undo',
	'REDO'							=> 'redo',
	
	'TOPIC_COLOR_PRESET'				=> 'presets',
	'TOPIC_COLOR_PRESET_MIN'			=> 'topic color preset min',
	'TOPIC_COLOR_PRESET_MIN_EXPLAIN'	=> '',	
	'TOPIC_COLOR_PRESET_MAX'			=> 'topic color preset max',
	'TOPIC_COLOR_PRESET_MAX_EXPLAIN'	=> '',	
	'FORUM_COLOR_PRESET_MIN'			=> 'forum color preset min',
	'FORUM_COLOR_PRESET_MIN_EXPLAIN'	=> '',	
	'FORUM_COLOR_PRESET_MAX'			=> 'forum color preset max',
	'FORUM_COLOR_PRESET_MAX_EXPLAIN'	=> '',		
	
	
	
));

?>