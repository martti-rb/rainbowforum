<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);

include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_calendar.' . $phpEx);

// Start session management
$user->session_begin();
$auth->acl($user->data);

// Language file
$user->setup('calendar');

if ($user->data['user_id'] == ANONYMOUS || $user->data['user_type'] == USER_INACTIVE || $user->data['user_type'] == USER_IGNORE)
{
    login_box('', $user->lang['LOGIN']);
}

page_header($user->lang['CALENDAR']);

// Initial var setup

$today_jd = floor(((time() + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600)) / 86400) + 2440588);

$post_data['start_jd'] = 			request_var('start_jd', ($today_jd - CALENDAR_DAYS_OFFSET));
$post_data['mid_jd'] = 				request_var('mid_jd', 0);
$post_data['forum_list'] =			request_var('fc',array(0 => 0));
$post_data['topic_id'] = 			request_var('t', 0);
$post_data['display_birthdays'] = 	request_var('dibi', 0);
$post_data['days_num'] =			request_var('days_num', 112);
$post_data['tables_num'] =			request_var('tables_num', 4);
$post_data['show_today'] = 			request_var('show_today', 1);
$post_data['addf'] =				request_var('addf', '');
$post_data['view'] =				request_var('view', '');


$post_data['submit'] =	(isset($_POST['set']) || $post_data['view'] == 'print') ? true : false;
$post_data['reset']	=	(isset($_POST['reset'])) ? true : false;

$days_offset = 0;

$post_data['start_jd'] += $days_offset;

$post_data['start_jd'] = ($post_data['mid_jd']) ? $post_data['mid_jd'] - 56 : $post_data['start_jd'];
	
// weekdays in month's colors

$mpat0 = new calendar_pattern(array(
	'mm00', 'mm01', 'mm00', 'mm01', 'mm00', 'mm01', 'mm01'
	));	
$mpat1 = new calendar_pattern(array(
	'mm10', 'mm11', 'mm10', 'mm11', 'mm10', 'mm11', 'mm11'
	));	
$mpat2 = new calendar_pattern(array(
	'mm20', 'mm21', 'mm20', 'mm21', 'mm20', 'mm21', 'mm21'
	));	
$mpat3 = new calendar_pattern(array(
	'mm30', 'mm31', 'mm30', 'mm31', 'mm30', 'mm31', 'mm31'
	));	
$mpat4 = new calendar_pattern(array(
	'mm40', 'mm41', 'mm40', 'mm41', 'mm40', 'mm41', 'mm41'
	));	
$mpat5 = new calendar_pattern(array(
	'mm50', 'mm51', 'mm50', 'mm51', 'mm50', 'mm51', 'mm51'
	));	
$mpat6 = new calendar_pattern(array(
	'mm60', 'mm61', 'mm60', 'mm61', 'mm60', 'mm61', 'mm61'
	));
	
$month_weeks_class_pattern = new calendar_pattern(array(
	$mpat0, $mpat1, $mpat2, $mpat3, $mpat4, $mpat5, $mpat6
	));

$month_class_pattern = new calendar_pattern(array(
	'mm01', 'mm11', 'mm21', 'mm31', 'mm41', 'mm51', 'mm61'
	));

	
calendar::init($today_jd, $post_data);
calendar::set_cols($month_weeks_class_pattern, 'today-mid');
calendar::insert_month_year_row($month_class_pattern);
calendar::insert_month_days_row('today-top');
calendar::insert_week_days_row();
calendar::insert_mooniso_row();
calendar::insert_days_row();
calendar::insert_events_rows();
if ($config['calendar_birthdays'])
{
	calendar::insert_birthday_rows();
}
calendar::insert_month_days_row('today-bottom');
calendar::write_to_template();


$template->set_filenames(array(
    'body' => ($post_data['view'] == 'print') ? 'calendar_print.html' : 'calendar_body.html'
	));

make_jumpbox(append_sid($phpbb_root_path . 'viewforum.' . $phpEx));
page_footer();
?>
