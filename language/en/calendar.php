<?php

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
	'CALENDAR_COMMENT'		=> 'the dates and times of the displayed moonphases are corresponding to the timezone setting in the user control panel.',
	
	'Sunday'	=> 'Sunday',
	'Monday'	=> 'Monday',
	'Tuesday'	=> 'Tuesday',
	'Wednesday'	=> 'Wednesday',
	'Thursday'	=> 'Thursday',
	'Friday'	=> 'Friday',
	'Saturday'	=> 'Saturday',

	'SUN'		=> 'Sun',
	'MON'		=> 'Mon',
	'TUE'		=> 'Tue',
	'WED'		=> 'Wed',
	'THU'		=> 'Thu',
	'FRI'		=> 'Fri',
	'SAT'		=> 'Sat',

	'JANUARY'	=> 'January',
	'FEBRUARY'	=> 'February',
	'MARCH'		=> 'March',
	'APRIL'		=> 'April',
	'MAY'		=> 'May',
	'JUNE'		=> 'June',
	'JULY'		=> 'July',
	'AUGUST'	=> 'August',
	'SEPTEMBER' => 'September',
	'OCTOBER'	=> 'October',
	'NOVEMBER'	=> 'November',
	'DECEMBER'	=> 'December',
	
	'PRINT_TOPIC'				=> 'print view',
	
	'REGION'					=> 'region',
	'TYPE'						=> 'type',
	'SELECT_ALL'				=> 'select all',
	'DESELECT_ALL'				=> 'deselect all',
	'DISPLAY_BIRTHDAYS'			=> 'display birthdays',
	'VIEW'						=> 'view',
	

	'BIRTHDAY_OF'				=> 'birthday of',
	
	
));

?>
