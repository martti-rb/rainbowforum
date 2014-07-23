<?php
/**
*
* @package phpBB3
* @version $Id: cron.php 8479 2008-03-29 00:22:48Z naderman $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*/
define('IN_PHPBB', true);
define('IN_CRON', true);
$phpbb_root_path = (defined('PHPBB_ROOT_PATH')) ? PHPBB_ROOT_PATH : './';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
include($phpbb_root_path . 'common.' . $phpEx);

// Do not update users last page entry
$user->session_begin(false);
$auth->acl($user->data);

		$time_now = time();



		$today_jd = floor($time_now / 86400) + 2440588;
		
		$time_edit_limit = $time_now - $config['auto_archive_edit_time'];

		$topic_id_to_skip = -1;
		$topic_ids = array();
		$log_topic_names = '';



		$sql = 'SELECT t.topic_id, t.topic_title, f.auto_archive_after_days, clndr.max_end_jd
			FROM ' . TOPICS_TABLE . ' t, ' . FORUMS_TABLE . ' f, 
			(SELECT topic_id, MAX(end_jd) AS max_end_jd FROM 
			 ' . CALENDAR_PERIODS_TABLE . ' GROUP BY topic_id) AS clndr	
			WHERE clndr.topic_id = t.topic_id
				AND f.forum_id = t.forum_id
				AND f.auto_archive_enable = 1
				AND f.forum_id <> ' . $config['archive_id'] . '
				AND t.topic_type <> ' . POST_ANNOUNCE . ' 
				AND t.topic_type <> ' . POST_STICKY . '
				AND t.topic_type <> ' . POST_GLOBAL . '
				AND t.topic_last_post_time < ' . $time_edit_limit . '				
				AND clndr.max_end_jd <= ' . $today_jd . '				
				ORDER BY t.topic_id ASC, clndr.max_end_jd DESC';

/*		$sql = 'SELECT t.topic_id, t.topic_title, c.end_jd, f.auto_archive_after_days
			FROM ' . CALENDAR_PERIODS_TABLE . ' c
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_id = c.topic_id)
			LEFT JOIN ' . FORUMS_TABLE . ' f ON (f.forum_id = t.forum_id)
			WHERE f.auto_archive_enable = 1
				AND f.forum_id <> ' . $config['archive_id'] . '
				AND t.topic_type <> ' . POST_ANNOUNCE . ' 
				AND t.topic_type <> ' . POST_STICKY . '
				AND t.topic_type <> ' . POST_GLOBAL . '
				AND t.topic_last_post_time < ' . $time_edit_limit . '
				AND c.end_jd <= ' . $today_jd . '
				ORDER BY t.topic_id ASC, c.end_jd DESC';

*/



		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			if ((($row['auto_archive_after_days'] + $row['max_end_jd']) <= $today_jd) &&
				($row['topic_id'] <> $topic_id_to_skip))
			{
				$topic_ids[] = $row['topic_id'];
				$log_topic_names .= '[' . $row['topic_id'] . ']' . $row['topic_title'] . ', ';
			}
			
			$topic_id_to_skip = $row['topic_id'];
		}

		$db->sql_freeresult($result);
		unset($row);		

		if (sizeof($topic_ids))
		{
			
			foreach($topic_ids as $topic_id)
			{
				echo $topic_id;
				echo '<br/>';
			}
		//	include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);	
			
	//		move_topics($topic_ids, $config['archive_id']);

		//	add_log('admin', 'LOG_AUTO_ARCHIVE', '(' . sizeof($topic_ids) . '), ' . $log_topic_names);
		}






?>
