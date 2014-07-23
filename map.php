<?php
define('IN_PHPBB', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);


include($phpbb_root_path . 'common.' . $phpEx);
/*include($phpbb_root_path . 'includes/functions_calendar.' . $phpEx); */

// Start session management
$user->session_begin();
$auth->acl($user->data);

// Language file
$user->setup('map');

if ($user->data['user_id'] == ANONYMOUS)
{
    login_box('', $user->lang['LOGIN']);
}

page_header($user->lang['MAP']);


//
// Initial var setup

$today_jd = floor(((time() + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600)) / 86400) + 2440588);

$post_data['start_jd'] = 			request_var('start_jd', ($today_jd - CALENDAR_DAYS_OFFSET));
$post_data['forum_list'] =			request_var('fc',array(0 => 0));
$post_data['cal_topic'] = 			request_var('t',0);
$post_data['display_birthdays'] = 	request_var('db',0);
$post_data['days_num'] =			request_var('days_num', CALENDAR_DISPLAY_DAYS);
$post_data['tables_num'] =			request_var('tables_num', 4);
$post_data['show_today'] = 			request_var('show_today', 1);
$post_data['addf'] =				request_var('addf', '');
$post_data['view'] =				request_var('view', '');

$post_data['fg1'] =					request_var('fg1', array(0 => 0));
$post_data['fg2'] =					request_var('fg2', array(0 => 0));

$post_data['submit'] =	(isset($_POST['set']) || isset($_POST['min112']) || isset($_POST['min56']) || isset($_POST['min28']) || isset($_POST['plus28']) || isset($_POST['plus56']) || isset($_POST['plus112']) || $post_data['view'] == 'print') ? true : false;
$post_data['save'] =	(isset($_POST['save'])) ? true : false;
$post_data['reset']	=	(isset($_POST['reset'])) ? true : false;

$days_offset = 0;

if ($post_data['submit'])
{
	if (isset($_POST['min112'])) 
	{
		$days_offset = -112;
	}
	else if (isset($_POST['min56']))
	{
		$days_offset = -56;	
	}
	else if (isset($_POST['min28']))
	{
		$days_offset = -28;	
	}
	else if (isset($_POST['plus28']))
	{
		$days_offset = 28;
	}
	else if (isset($_POST['plus56']))
	{
		$days_offset = 56;	
	}
	else if (isset($_POST['plus112']))
	{
		$days_offset = 112;	
	}
}

$post_data['start_jd'] += $days_offset;
	
// weekdays in month's colors

/*

$mpat0 = new calendar_pattern(array(
	'mm00', 'mm01', 'mm00', 'mm01', 'mm00', 'mm01', 'mm01'
	));
	


//
	
calendar::init(array(
	'days_num'			=> $post_data['days_num'],
	'tables_num'		=> $post_data['tables_num'],
	'start_jd'			=> $post_data['start_jd'],
	'class'				=> $month_weeks_class_pattern,
	'today_jd'			=> $today_jd,
	'show_today'		=> $post_data['show_today'],	
	'post_data'			=> $post_data
	));
	
calendar::init_row(array(
	'text'				=> $month_names_pattern,
	'class'				=> $month_class_pattern,
	'type'				=> calendar::GREG_MONTH_YEAR
	));
	
calendar::init_row(array(
	'type'				=> calendar::GREG_MONTHDAYS,
	'today_class'		=> 'today-top'
	));
	
calendar::init_row(array(
	'text'				=> $week_days_names_pattern,
	'type'				=> calendar::DAYS_TYPE,
	'today_class'		=> 'today-mid'
	));
	
calendar::init_row(array(
	'type'				=> calendar::MOONPHASE_ISOWEEK,
	'today_class'		=> 'today-mid'
	));
	
calendar::init_row(array(
	'type'				=> calendar::DAYS_TYPE,
	'today_class'		=> 'today-mid'
	));

calendar::init_row(array(
	'type'				=> calendar::EVENTS_TYPE,
	'today_class'		=> 'today-mid'
	));

calendar::init_row(array(
	'type'				=> calendar::DAYS_TYPE,
	'today_class'		=> 'today-mid'
	));
	
calendar::init_row(array(
	'type'				=> calendar::GREG_MONTHDAYS,
	'today_class'		=> 'today-bottom'
	));

calendar::write_to_template();
*/
$template->assign_vars(array(
	'GOOGLE_API_KEY'	=> $config['google_api_key'],
	));



$template->set_filenames(array(
    'body' => ($post_data['view'] == 'print') ? 'map_print.html' : 'map_body.html'
	));

make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
page_footer();
?>