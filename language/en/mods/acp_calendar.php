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

	'CALENDAR_GENERAL_SETTINGS'		=> 'general',
	'CALENDAR_ENABLE'				=> 'calendar enable',
	'CALENDAR_BIRTHDAYS'			=> 'list birthdays on calendar page',
	
	'CALENDAR_DISPLAY_SUFFIX'		=> 'suffix display',
	'CALENDAR_ENABLE_SUFFIX'		=> 'suffix enable',
	
	'CALENDAR_SUFFIX_CONTEXT'		=> 'suffix context',
	'CALENDAR_MULTI_SUFFIX'			=> 'multi suffix',

	'CALENDAR_MONTH_ABBREV'			=> 'Use Month abbreviations',
	
	'CALENDAR_FILTER'				=> 'Calendar filter',
	'CALENDAR_ENABLE_FILTER'		=> 'Calendar enable filter',

	'CALENDAR_INPUT_FORUMS_SELECT'	=> 'Input forums select',
	'CALENDAR_INPUT_FORUMS'			=> 'Input forums',
	'CALENDAR_INPUT_FORUMS_EXPLAIN' => '',
	
	'CALENDAR_DATE_INPUTS'			=> 'Date Inputs',
	
	'CALENDAR_MIN_START'			=> 'start day possible minus days from now',
	'CALENDAR_MIN_START_EXPLAIN'	=> '',	
	
	'CALENDAR_PLUS_START'			=> 'start day possible plus days from now',	
	'CALENDAR_PLUS_START_EXPLAIN'	=> '',

	'CALENDAR_MAX_PERIOD_LENGTH'			=> 'maximum period length in days',
	'CALENDAR_MAX_PERIOD_LENGTH_EXPLAIN'	=> '',	
	
	'CALENDAR_MAX_PERIODS'			=> 'maximum number of calendar periods input per topic',	
	'CALENDAR_MAX_PERIODS_EXPLAIN'	=> 'setting to zero disables attaching calendar periods to topics.',	

	'CALENDAR_MIN_GAP'				=> 'minimum gap between periods of one topic in days',
	'CALENDAR_MIN_GAP_EXPLAIN'		=> '',	
	
	'CALENDAR_MAX_GAP'				=> 'maximum gap between periods of one topic in days',	
	'CALENDAR_MAX_GAP_EXPLAIN'		=> '',

	'CALENDAR_PRESET'				=> 'show topics in calendar (preset)',	
	'CALENDAR_PRESET_EXPLAIN'		=> '',	
	
	
	'CALENDAR_PAGE_FORUMS_DISPLAY'	=> 'Forums display on Calendar Page.',
	
	'MAX_PERIODS'			=> 'max. periods',
	'MAX_PERIOD_LENGTH'		=> 'max. period length',
	'MIN_START'				=> 'min. start',
	'MAX_START'				=> 'max. start',
	'MIN_GAP'				=> 'min. gap',
	'MAX_GAP'				=> 'max. gap',
	'PRESET'				=> 'preset',

	
	
));

?>