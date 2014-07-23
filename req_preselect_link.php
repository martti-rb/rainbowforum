<?php
/**
*
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
* Minimum Requirement: PHP 4.3.3
*/

define('IN_PHPBB', true);

$phpbb_root_path = './';
$phpEx = 'php';

$link_image = $_POST['link_image'];
$link_status = $_POST['link_status'];
$link_type = $_POST['link_type'];
$link_source = $_POST['link_source'];
$link_forum = $_POST['link_forum'];
$link_location = $_POST['link_loc'];
$link_prefix = $_POST['link_prefix'];

$loc_a = $_POST['loc_a'];
$loc_b = $_POST['loc_b'];
$loc_c = $_POST['loc_c'];

$page_time = $_POST['page_time'];
$link_forum_id = $_POST['link_forum_id'];

$forum_id = $_POST['forum_id'];
$prefix_id = $_POST['prefix_id'];

if (!$page_time || !$link_forum_id || $link_image == '' || $link_status == '' || $link_type == '' || $link_source == '' || $link_forum == '' || $link_location == '' || $forum_id == '')
{
	echo('');
	exit;
}

if (file_exists('./config.php'))
{
	require('./config.php');
}

require('./includes/constants.php');
require('./includes/db/' . $dbms . '.php');
$db = new $sql_db();
$db->sql_connect($dbhost, $dbuser, $dbpasswd, $dbname, $dbport, false, defined('PHPBB_DB_NEW_LINK') ? PHPBB_DB_NEW_LINK : false);
unset($dbpasswd);

$link_list = array(0);

$sql = 'SELECT t.topic_id
		FROM ' . TOPICS_TABLE . ' t 
		WHERE t.topic_is_link = 1
			AND t.forum_id = ' . $link_forum_id . '
		ORDER BY t.topic_title ASC';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$link_list[] = $row['topic_id'];
}

$db->sql_freeresult($result);

$sql = 'SELECT f.parent_id, f.forum_id
		FROM ' . FORUMS_TABLE . ' f';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$forum_ary[$row['forum_id']][] = $row['forum_id'];
	if ($row['parent_id'])
	{
		$forum_ary[$row['parent_id']][] = $row['forum_id'];
	}
}

$db->sql_freeresult($result);


$link_index_list = array_flip($link_list);

$sel_ary = array(
	'status'	=> array('sel' => $link_status),
	'type'		=> array('sel' => $link_type),
	'source'	=> array('sel' => $link_source),
	);
	
foreach($sel_ary as $key => &$ary)
{
	$ary['options'] = array(
		'0'		=> '',
		'no'	=> ' AND t.topic_link_' . $key . '_id = 0',
		'with'	=> ' AND t.topic_link_' . $key . '_id <> 0',
		'line'	=> ' AND 1 = 0',
		);

	$o_ary = explode('_', $_POST[$key . '_str']);
	
	foreach($o_ary as $value)
	{
		$ary['options'][$value] = ' AND t.topic_link_' . $key . '_id = ' . $value;
	}		
}		
		
unset($ary);

$sel_ary['forum'] = array(
	'sel'		=> $link_forum,
	'options'	=> array(
		'0'		=> '',
		'no'	=> ' AND t.topic_link_forum_id = 0',
		'with'	=> ' AND t.topic_link_forum_id <> 0',
		'this'	=> ' AND ' . $db->sql_in_set('t.topic_link_forum_id', $forum_ary[$forum_id]),
		'line'	=> ' AND 1 = 0',		
	));
	
$o_ary = explode('_', $_POST['forum_str']);

foreach($o_ary as $value)
{
	$sel_ary['forum']['options'][$value] = ' AND ' . $db->sql_in_set('t.topic_link_forum_id', $forum_ary[$value]);	
}

$sel_ary['prefix'] = array(
	'sel'		=> $link_prefix,
	'options'	=> array(
		'0'		=> '',
		'no'	=> ' AND t.topic_prefix_id = 0',
		'with'	=> ' AND t.topic_prefix_id <> 0',
		'this'	=> ' AND t.topic_prefix_id = ' . $prefix_id,
		'line'	=> ' AND 1 = 0',		
	));	

$o_ary = explode('_', $_POST['prefix_str']);

foreach($o_ary as $value)
{
	$sel_ary['prefix']['options'][$value] = ' AND t.topic_prefix_id = ' . $value;	
}

$sel_ary['image'] = array(
	'sel'		=> $link_image,
	'options' 	=> array(
		'0'		=> '',
		'no'	=> ' AND t.topic_thumbnail_ext = \'\'',
		'with'	=> ' AND t.topic_thumbnail_ext <> \'\'',	
		));
$sel_ary['loc'] = array(
	'sel'		=> $link_location,
	'options' 	=> array(
		'0'		=> '',
		'no'	=> ' AND t.location_a1 = 0',
		'with'	=> ' AND t.location_a1 <> 0',
		'cont'	=> ' AND t.location_a1 = ' . $loc_a,
		'full'	=> ' AND (t.location_a1 = ' . $loc_a . 
					' AND t.location_b1 = ' . $loc_b .
					' AND t.location_c1 = ' . $loc_c . ')',
		'line'	=> ' AND 1 = 0',					
		)); 	

$o_ary = explode('_', $_POST['loc_str']);

foreach($o_ary as $value)
{
	$sel_ary['loc']['options'][$value] = ' AND t.location_a1 = ' . $value;	
}

		
$sql_all_ary = array();
		
foreach($sel_ary as $key => $ary)
{
	$sql_all_ary[$key] = $ary['options'][$ary['sel']];  
}

$sql_all = implode('', $sql_all_ary);

$link_ary = array();

$sql = 'SELECT t.topic_id
		FROM ' . TOPICS_TABLE . ' t
		WHERE t.topic_is_link = 1
			AND t.forum_id = ' . $link_forum_id . '
			AND t.topic_time < ' . $page_time . 
			$sql_all . '
		ORDER BY topic_title ASC';
$result = $db->sql_query($sql);

while ($row = $db->sql_fetchrow($result))
{
	$link_ary[] = $link_index_list[$row['topic_id']];
}

$db->sql_freeresult($result);

$return_ary = array('links' => $link_ary);

foreach ($sel_ary as $key => $ary)
{
	$sql_copy_ary = $sql_all_ary;
	$count_ary = array();

	foreach($ary['options'] as $val => $sql_option)
	{
		$sql_copy_ary[$key] = $sql_option;
		$sql_where = implode('', $sql_copy_ary);

		$sql = 'SELECT COUNT(DISTINCT t.topic_id) AS num_topics 
				FROM ' . TOPICS_TABLE . ' t
				WHERE t.topic_is_link = 1
			AND t.forum_id = ' . $link_forum_id . '
			AND t.topic_time < ' . $page_time . $sql_where;
		$result = $db->sql_query($sql);
		$count_ary[] = (int) $db->sql_fetchfield('num_topics');
		$db->sql_freeresult($result);	
	}
	
	$return_ary = array_merge($return_ary, array($key => $count_ary));
}

echo(json_encode($return_ary));

?>