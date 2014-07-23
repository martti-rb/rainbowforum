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

	'LOCATION_TAGS_SETTINGS_UPDATE_SUCCESSFUL'	=> 'The update of the location tags settings was successful.',
	'LOCATION_TAGS_GENERAL'						=> 'general',

	'LOCATION_TAGS_ENABLE'					=> 'Location tags enable',
	'LOCATION_TAGS_ENABLE_EXPLAIN'			=> 'Makes it possible to add a location tag to the topics',
	
	'FORUMS_SELECT'							=> 'Input forums select',
	'FORUMS_SELECT_EXPLAIN'					=> 'The forums where topics can get location tag(s).',
	'ALL_FORUMS'							=> 'all forums',
	'LOCATIONS_PER_TOPIC'					=> 'Location tags per topic.',
	'LOCATIONS_PER_TOPIC_EXPLAIN'			=> 'The maximum amount of location tags each topic can get in the forums selected above.',
));

?>