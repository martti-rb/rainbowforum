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

$cron_type = request_var('cron_type', '');

// Output transparent gif
header('Cache-Control: no-cache');
header('Content-type: image/gif');
header('Content-length: 43');

echo base64_decode('R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==');

// Flush here to prevent browser from showing the page as loading while running cron.
flush();

if (!isset($config['cron_lock']))
{
	set_config('cron_lock', '0', true);
}

// make sure cron doesn't run multiple times in parallel
if ($config['cron_lock'])
{
	// if the other process is running more than an hour already we have to assume it
	// aborted without cleaning the lock
	$time = explode(' ', $config['cron_lock']);
	$time = $time[0];

	if ($time + 3600 >= time())
	{
		exit;
	}
}

define('CRON_ID', time() . ' ' . unique_id());

$sql = 'UPDATE ' . CONFIG_TABLE . "
	SET config_value = '" . $db->sql_escape(CRON_ID) . "'
	WHERE config_name = 'cron_lock' AND config_value = '" . $db->sql_escape($config['cron_lock']) . "'";
$db->sql_query($sql);

// another cron process altered the table between script start and UPDATE query so exit
if ($db->sql_affectedrows() != 1)
{
	exit;
}

/**
* Run cron-like action
* Real cron-based layer will be introduced in 3.2
*/
switch ($cron_type)
{
	case 'queue':

		if (time() - $config['queue_interval'] <= $config['last_queue_run'] || !file_exists($phpbb_root_path . 'cache/queue.' . $phpEx))
		{
			break;
		}

		include_once($phpbb_root_path . 'includes/functions_messenger.' . $phpEx);
		$queue = new queue();

			$queue->process();

	break;

	case 'tidy_cache':

		if (time() - $config['cache_gc'] <= $config['cache_last_gc'] || !method_exists($cache, 'tidy'))
		{
			break;
		}

			$cache->tidy();

	break;

	case 'tidy_search':
		
		// Select the search method
		$search_type = basename($config['search_type']);

		if (time() - $config['search_gc'] <= $config['search_last_gc'] || !file_exists($phpbb_root_path . 'includes/search/' . $search_type . '.' . $phpEx))
		{
			break;
		}

		include_once("{$phpbb_root_path}includes/search/$search_type.$phpEx");

		// We do some additional checks in the module to ensure it can actually be utilised
		$error = false;
		$search = new $search_type($error);

		if ($error)
		{
			break;
		}

			$search->tidy();

	break;

	case 'tidy_warnings':

		if (time() - $config['warnings_gc'] <= $config['warnings_last_gc'])
		{
			break;
		}

		include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

			tidy_warnings();

	break;

	case 'tidy_database':

		if (time() - $config['database_gc'] <= $config['database_last_gc'])
		{
			break;
		}

		include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

			tidy_database();

	break;

	case 'tidy_sessions':

		if (time() - $config['session_gc'] <= $config['session_last_gc'])
		{
			break;
		}

			$user->session_gc();

	break;

	case 'prune_forum':

		$forum_id = request_var('f', 0);

		$sql = 'SELECT forum_id, prune_next, enable_prune, prune_days, prune_viewed, forum_flags, prune_freq
			FROM ' . FORUMS_TABLE . "
			WHERE forum_id = $forum_id";
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if (!$row)
		{
			break;
		}

		// Do the forum Prune thang
		if ($row['prune_next'] < time() && $row['enable_prune'])
		{
			include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);

			if ($row['prune_days'])
				{
					auto_prune($row['forum_id'], 'posted', $row['forum_flags'], $row['prune_days'], $row['prune_freq']);
			}

			if ($row['prune_viewed'])
				{
					auto_prune($row['forum_id'], 'viewed', $row['forum_flags'], $row['prune_viewed'], $row['prune_freq']);
			}
		}

	break;
	
// auto archive mod - begin
	
	case 'auto_archive':

		$time_now = time();

		if ($time_now - $config['auto_archive_gc'] <= $config['auto_archive_last_gc'])
		{
			break;
		}

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
			include_once($phpbb_root_path . 'includes/functions_admin.' . $phpEx);	
			
			move_topics($topic_ids, $config['archive_id']);

			add_log('admin', 'LOG_AUTO_ARCHIVE', '(' . sizeof($topic_ids) . '), ' . $log_topic_names);
		}

		set_config('auto_archive_last_gc', $time_now, true);

	break;	
// auto archive mod - end
}

// Unloading cache and closing db after having done the dirty work.
	unlock_cron();
	garbage_collection();

exit;


/**
* Unlock cron script
*/
function unlock_cron()
{
	global $db;

	$sql = 'UPDATE ' . CONFIG_TABLE . "
		SET config_value = '0'
		WHERE config_name = 'cron_lock' AND config_value = '" . $db->sql_escape(CRON_ID) . "'";
	$db->sql_query($sql);
}

?>
