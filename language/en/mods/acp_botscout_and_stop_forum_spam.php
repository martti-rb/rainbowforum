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

	'BOTSCOUT_LEGEND'			=> 'Botscout settings',
	'BOTSCOUT_ENABLE'			=> 'Botscout enable',
	'BOTSCOUT_EXPLAIN'			=> 'scan Botscout on new registration.',
	
	'BS_API_KEY'				=> 'Botscout api key',
	
	'BS_IP'						=> 'max ip in Botscout database',
	'BS_EMAIL'					=> 'max email in Botscout database',
	'BS_USERNAME'				=> 'max username in Botscout database',	
	
	'STOP_FORUM_SPAM_LEGEND'	=> 'Stop Forum Spam settings',
	'STOP_FORUM_SPAM_ENABLE'	=> 'Stop Forum Spam enable',
	'STOP_FORUM_SPAM_EXPLAIN'	=> 'scan Stop Forum Spam on new registration.',	
	
	'SFS_API_KEY'				=> 'Stop Forum Spam api key',

	'SFS_IP'					=> 'max ip in Stop Forum Spam databasee',
	'SFS_EMAIL'					=> 'max email in Stop Forum Spam database',
	'SFS_USERNAME'				=> 'max username in Stop Forum Spam database',	

	
));

?>