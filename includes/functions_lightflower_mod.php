<?php
/**
*
* @package phpBB3
* @version $Id: functions.php 10519 2010-02-22 00:59:27Z toonarmy $
* @copyright (c) 2005 phpBB Group
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}


// calendar mod begin

function obtain_calendar_suffixes($topic_list, $current_time = NULL, $multiple = false)
{
	global $config, $user, $db;
	
	if (!is_array($topic_list))
	{
		$topic_list = array($topic_list);
	}	
	
	if (!$config['calendar_enable'] || !$config['calendar_enable_suffix'] || !sizeof($topic_list))
	{
		return false;
	}

	if (!isset($current_time))
	{
		$current_time = time() + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600);
	}

	$today_jd = floor($current_time / 86400) + 2440588;
	
	unset($calendar_data);

	$sql = 'SELECT topic_id, start_jd, end_jd
			FROM ' . CALENDAR_PERIODS_TABLE . '
			WHERE ' . $db->sql_in_set('topic_id', $topic_list) . '
			ORDER BY start_jd ASC';
	$result = $db->sql_query($sql);
	
	while ($row = $db->sql_fetchrow($result))
	{
		$i = (isset($count[$row['topic_id']])) ? $count[$row['topic_id']] : 0;
		$data[$row['topic_id']][$i]['start_jd'] = $row['start_jd'];
		$data[$row['topic_id']][$i]['end_jd'] = $row['end_jd'];
		$count[$row['topic_id']]++;
	}
	$db->sql_freeresult($result);
	unset($row);
	
	if (!sizeof($data))
	{
		return false;
	}
	
	if ($config['calendar_month_abbrev'])
	{
		$cal_months = array(
			'---',
			$user->lang['datetime']['Jan'],
			$user->lang['datetime']['Feb'],
			$user->lang['datetime']['Mar'],
			$user->lang['datetime']['Apr'],
			$user->lang['datetime']['May_short'],
			$user->lang['datetime']['Jun'],
			$user->lang['datetime']['Jul'],
			$user->lang['datetime']['Aug'],
			$user->lang['datetime']['Sep'],
			$user->lang['datetime']['Oct'],
			$user->lang['datetime']['Nov'],
			$user->lang['datetime']['Dec']
		);	
	}
	else
	{
		$cal_months = array(
			'---',
			$user->lang['datetime']['January'],
			$user->lang['datetime']['February'],
			$user->lang['datetime']['March'],
			$user->lang['datetime']['April'],
			$user->lang['datetime']['May'],
			$user->lang['datetime']['June'],
			$user->lang['datetime']['July'],
			$user->lang['datetime']['August'],
			$user->lang['datetime']['September'],
			$user->lang['datetime']['October'],
			$user->lang['datetime']['November'],
			$user->lang['datetime']['December']
		);
	}

	foreach ($data as $topic_id => $cal_periods)
	{
		if (sizeof($cal_periods) > 1)
		{
			foreach ($cal_periods as $key_period => $cal_period)
			{
				if ($today_jd >= $cal_period['start_jd'] && $today_jd <= $cal_period['end_jd'])
				{
					$single = $key_period;
					$context = 'CALENDAR_SUFFIX_NOW';
					break;
				}
				else if ($today_jd < $cal_period['start_jd'])
				{
					$single = $key_period;
					$context = 'CALENDAR_SUFFIX_NEXT';
					break;
				}
				else
				{
					$single = $key_period;
					$context = 'CALENDAR_SUFFIX_LAST';
				}
			}
		}
		else
		{
			$single = 0;
			$context = 'CALENDAR_SUFFIX_SINGLE';
		}
		
		$context = ($config['calendar_suffix_context']) ? $user->lang[$context] : '';		
		
		if ($multiple)
		{	
			foreach ($cal_periods as $key_period => $cal_period)
			{
				$start_greg = cal_from_jd($cal_period['start_jd'], CAL_GREGORIAN);
				$end_greg = cal_from_jd($cal_period['end_jd'], CAL_GREGORIAN);

				if ($start_greg['year'] == $end_greg['year'])
				{
					if ($start_greg['month'] == $end_greg['month'])
					{
						if ($start_greg['day'] == $end_greg['day'])
						{
							$format = 'MONTH_DAY_YEAR';
						}
						else
						{
							$format = 'MONTH_DAY_DAY_YEAR';							
						}
					}
					else
					{
						$format = 'MONTH_DAY_MONTH_DAY_YEAR';				}
				} 
				else
				{
					$format = 'MONTH_DAY_YEAR_MONTH_DAY_YEAR';
				}
				
				$suffix_ary = array(
					$cal_months[$start_greg['month']], 
					$start_greg['day'],
					$start_greg['year'],
					$cal_months[$end_greg['month']],
					$end_greg['day'],
					$end_greg['year']
					);			

				$suffix_string = vsprintf($user->lang['calendar_suffix_format'][$format], $suffix_ary);
				
				$suffix_data[$topic_id]['multi'][$key_period]['suffix'] = $suffix_string;
				$suffix_data[$topic_id]['multi'][$key_period]['start_jd'] = $cal_period['start_jd'];
				
				if ($key_period == $single)
				{		
					$suffix_data[$topic_id]['single']['suffix'] = $context . $suffix_string;
					$suffix_data[$topic_id]['single']['start_jd'] = $cal_period['start_jd'];	
				}	
			}
		}
		else
		{
			$start_greg = cal_from_jd($cal_periods[$single]['start_jd'], CAL_GREGORIAN);
			$end_greg = cal_from_jd($cal_periods[$single]['end_jd'], CAL_GREGORIAN);

			if ($start_greg['year'] == $end_greg['year'])
			{
				if ($start_greg['month'] == $end_greg['month'])
				{
					if ($start_greg['day'] == $end_greg['day'])
					{
						$format = 'MONTH_DAY_YEAR';
					}
					else
					{
						$format = 'MONTH_DAY_DAY_YEAR';							
					}
				}
				else
				{
					$format = 'MONTH_DAY_MONTH_DAY_YEAR';				}
			} 
			else
			{
				$format = 'MONTH_DAY_YEAR_MONTH_DAY_YEAR';
			}
			
			$suffix_ary = array(
				$cal_months[$start_greg['month']], 
				$start_greg['day'],
				$start_greg['year'],
				$cal_months[$end_greg['month']],
				$end_greg['day'],
				$end_greg['year']
				);			

			$suffix_string = vsprintf($user->lang['calendar_suffix_format'][$format], $suffix_ary);
			
			$suffix_data[$topic_id]['suffix'] = $context . $suffix_string;
			$suffix_data[$topic_id]['start_jd'] = $cal_periods[$single]['start_jd'];		
		}	
	}

	return $suffix_data;
}

// calendar mod end

/** Topic Title Prefix Mod - Begin
**
**/

function obtain_topic_title_prefixes($topic_list)
{
	global $db;

	if (!is_array($topic_list))
	{
		$topic_list = array($topic_list);
	}
	
	if (!sizeof($topic_list))
	{
		return false;
	}
	
	$sql = 'SELECT p.prefix_name, t.topic_id
		FROM ' . TTP_PREFIXES_TABLE . ' p 
		LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_prefix_id = p.prefix_id)
		WHERE ' . $db->sql_in_set('t.topic_id', $topic_list);
		
	$result = $db->sql_query($sql);
	
	while ($row = $db->sql_fetchrow($result))
	{
		$title_prefix[$row['topic_id']] = $row['prefix_name'];	
	}
	$db->sql_freeresult($result);
	unset($row);
	
	if (!sizeof($title_prefix))
	{
		return false;
	}		
		
	return $title_prefix;	
	
}

// Topic Title Prefix Mod - End

/** locations mod begin
**
**/

class topic_locations
{
	var $locations = array();
	var $topic_locations = array();
	
	function topic_locations()
	{
		global $cache;
		
		$this->locations = $cache->obtain_locations();
	}

	function set_topics($topic_list = array())
	{
		global $db, $user;
	
		if (!is_array($topic_list))
		{
			$topic_list = array($topic_list);
		}
		
		if (!sizeof($topic_list))
		{
			return false;
		}
			
		$sql = 'SELECT topic_id, location_a, location_b, location_c
			FROM ' . TOPICS_LOCATIONS_TABLE . '
			WHERE ' . $db->sql_in_set('topic_id', $topic_list);	
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			$this->topic_locations[$row['topic_id']][] = array(
				'location_a'	=>	array(
					'id'	=> $row['location_a'],
					'name'	=> $this->locations[$row['location_a']]),
				'location_b'	=>	array(
					'id'	=> $row['location_b'],
					'name'	=> $this->locations[$row['location_b']]),
				'location_c'	=>	array(
					'id'	=> $row['location_c'],
					'name'	=> $this->locations[$row['location_c']]),
					);	
		}	
		$db->sql_freeresult($result);

		if (!sizeof($this->topic_locations))
		{
			return false;
		}

		return $this->topic_locations;
	}
	
	function get_tag_string($topic_id)
	{
		global $user;
	
		if (!sizeof($this->topic_locations[$topic_id]))
		{
			return false;
		}	

		if (sizeof($this->topic_locations[$topic_id]) == 1)
		{
			$tag_string = $user->lang['LOCATION_TAG'] . ' : ';
			$tag_string .= $this->topic_locations[$topic_id][0]['location_a']['name'];
			
			if ($this->topic_locations[$topic_id][0]['location_b']['id'])
			{
				$tag_string .= ', ' . $this->topic_locations[$topic_id][0]['location_b']['name'];
				
				if ($this->topic_locations[$topic_id][0]['location_c']['id'])
				{
					$tag_string .= ', ' . $this->topic_locations[$topic_id][0]['location_c']['name'];	
				}			
			}
			
			return $tag_string;
		}
	
		$tag_string = $user->lang['LOCATION_TAGS'] . ' : ';

		foreach ($this->topic_locations[$topic_id] as $key => $topic_location)
		{
			$tag_string .= '[' . ($key + 1) . '] ';
			$tag_string .= $topic_location['location_a']['name'];
			
			if ($topic_location['location_b']['id'])
			{
				$tag_string .= ', ' . $topic_location['location_b']['name'];
				
				if ($topic_location['location_c']['id'])
				{
					$tag_string .= ', ' . $topic_location['location_c']['name'];	
				}			
			}

			$tag_string .= ($key == (sizeof($this->topic_locations[$topic_id]) - 1)) ? '' : ' ';

		}
		
		return $tag_string;	
	}
}

// locations mod end

/** Display Parent Forum Name Mod - Begin
**
**/

function obtain_parent_forum_name($forum_data)
{
	$parent_forum_name = '';

	if (isset($forum_data['forum_parents']) && $forum_data['display_parent_forum'])
	{
		$forum_parents = unserialize($forum_data['forum_parents']);
			
		if (is_array($forum_parents) && sizeof($forum_parents))
		{
			list($parent_forum_name) = end($forum_parents);

			$parent_forum_name = ($parent_forum_name) ? $parent_forum_name . ' <strong>&#8249;</strong> ' : '';
		} 				
	}
	
	return $parent_forum_name;
}			
			
// Display Parent Forum Name Mod - End

// link mod begin // circle mod begin

class lang_key_replacer
{
	var $search = false;
	var $replace = false;

	function lang_key_replacer($search = false, $replace = '')
	{
		$this->search = $search;
		$this->replace = $replace;	
	}
	
	function lang($key, $lang = true)
	{
		global $user;
		
		if ($this->search)
		{
			$key = str_replace($this->search, $this->replace, $key);
		}
		
		return ($lang) ? $user->lang[$key] : $key;
	}		
}

// link mod end // circle mod end

// circle mod begin

class circles_class // faulty
{
	var $hidden = array();
	var $code_en = array();
	var $public_archive = array();
	var $auto_archive = array();
	var $founder = array();

	function circles_class()
	{
		global $db;
		
		$vars = array('hidden', 'code_en', 'public_archive', 'auto_archive');
		
		$sql = 'SELECT topic_circle_id, topic_circle_hidden, topic_circle_code_en, topic_circle_public_archive, topic_circle_auto_archive 
					FROM ' . TOPICS_TABLE . '
					WHERE topic_is_circle = 1';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{	
			foreach ($vars as $var)
			{
				$this->{$var}[$row['topic_circle_id']] = $row['topic_circle_' . $var];
			}

			$this->founder[$row['topic_circle_id']] = $row['topic_poster'];				
		}
		$db->sql_freeresult($result);		
	}
}


// circle mod end

/** topic color mod begin
**
**/


class topic_color
{
	var $color_sets = array();
	
	function topic_color()
	{
		global $cache;
	
		$this->color_sets = $cache->obtain_color_sets();
	}
	
	function get_script_ary($string_id_ary = array())
	{
		global $config;
		
		if (!is_array($string_id_ary))
		{
			$string_id_ary = array($string_id_ary);
		}
		
		$color_set_ary = '';
		
		foreach ($string_id_ary as $string_id)
		{
			$color_set_id = $config['color_' . $string_id];
			
			$color_set_ary .= '[';
			
			for ($i = 0; $i < 8; $i++)
			{
				$color_set_ary .= '[' . $this->color_sets[$color_set_id][$i]['r'];
				$color_set_ary .= ', ' . $this->color_sets[$color_set_id][$i]['g'];				
				$color_set_ary .= ', ' . $this->color_sets[$color_set_id][$i]['b'] . '], ';				
			}
			
			$color_set_ary = rtrim($color_set_ary, ', ');			
			$color_set_ary .= '], ';
		}
		$color_set_ary = rtrim($color_set_ary, ', ');
		
		return $color_set_ary;
	}
	
	function topic_template($color_index, $dark)
	{
		global $template;

		$template->assign_vars(array(
			'S_TOPIC_COLOR'		=> true,
			'CLASS_TPCM_DARK'	=> ($dark) ? ' tpcm-dark' : '',
			'COLOR_BG1'			=> $this->obtain_color($color_index, 'topic', $dark, false),
			'COLOR_BG2'			=> $this->obtain_color($color_index, 'topic', $dark, true),
			'COLOR_ATTACH'		=> $this->obtain_color($color_index, 'attachment', $dark),
			'COLOR_QOUTE1'		=> $this->obtain_color($color_index, 'quote', $dark, false),
			'COLOR_QUOTE2'		=> $this->obtain_color($color_index, 'quote', $dark, true),
			'COLOR_CODE'		=> $this->obtain_color($color_index, 'code', $dark),
			));
	}

	function obtain_color($val = 0, $name = 'topic', $dark = false, $hi_tint = false)
	{
		global $config;
		
		if ($name == 'topic' || $name == 'quote')
		{
			$ch = ($dark) ? (($hi_tint) ? 'c' : 'd') : (($hi_tint) ? 'a' : 'b');
		}
		else
		{
			$ch = ($dark) ? 'b' : 'a';
		}
		
		$csi = $config['color_' . $name . '_' . $ch];
		
		$f1 = ($val % 80) / 80;
		$f2 = (80 - ($val % 80)) / 80;
		$i1 = floor($val/80);
		$i1 = ($i1 < 8) ? $i1 : $i1 - 1;

		if ($i1 == 7)  
		{
			$color = 'rgb(' . $this->color_sets[$csi][$i1]['r'] . ', ';
			$color .= $this->color_sets[$csi][$i1]['g'] . ', ';
			$color .= $this->color_sets[$csi][$i1]['b'] . ')';
		}
		else 
		{
			$i2 = $i1 + 1;
			
			$color = 'rgb(' . floor(($this->color_sets[$csi][$i1]['r'] * $f2) + ($this->color_sets[$csi][$i2]['r'] * $f1)) . ', ';
			$color .= floor(($this->color_sets[$csi][$i1]['g'] * $f2) + ($this->color_sets[$csi][$i2]['g'] * $f1)) . ', ';
			$color .= floor(($this->color_sets[$csi][$i1]['b'] * $f2) + ($this->color_sets[$csi][$i2]['b'] * $f1)) . ')'; 	
		} 

		return $color; 	
	}

}

// topic color mod end

/**
**
**/ 

// filter mod begin

class topic_filters
{
	var $lang_all = '';
	
	var $sql = '';
	var $u = '';
	var $type = '';
	var $options = '';
	
	var $filters = array();
	
	var $line = array(
		'sql'	=> ' AND 1 = 0',
		'text'	=> '-----------');
	
	var $sql_from = '';
	var $sql_base = ' 1';	
	var $sql_pre = '';
	var $sql_suf = '';
	var $sql_all = '';
	var $sql_all_ary = array();
	
	var $u_all = '';
	var $u_all_ary = array();
	
	var $u_base = '';
	var $page = '';

	// forum mode
	
	var $forum_id;
	var $forum_mode = false;
	
	// calendar mode

	var $start_jd;
	var $end_jd;
	var $calendar_mode = false;
	var $post_data = array();

	function topic_filters($lang_all)
	{
		$this->lang_all = $lang_all;
	}
	
	function init_forum_mode($forum_id, $sql_pre)
	{
		$this->forum_mode = true;	
		$this->forum_id = $forum_id;
		$this->sql_pre = $add_sql;
		$this->sql_base = ' t.forum_id = ' . $forum_id;
		$this->sql_from = ' FROM  ' . TOPICS_TABLE . ' t ';
		$this->u_base = '&f=' . $forum_id;
		$this->page = 'viewforum';
	}
	
	function init_calendar_mode($start_jd, $end_jd, $sql_pre, $post_data)
	{
		$this->calendar_mode = true;	
		$this->sql_pre = $sql_pre;
		$this->start_jd = $start_jd;
		$this->end_jd = $end_jd;
		$this->sql_base = ' (c.start_jd <= ' . $this->end_jd . ')
					AND (c.end_jd >= ' . $this->start_jd . ')';
		$this->sql_from = ' FROM  ' . TOPICS_TABLE . ' t 
					LEFT JOIN ' . CALENDAR_PERIODS_TABLE . ' c ON (c.topic_id = t.topic_id) ';
		$this->post_data = $post_data;
		$this->u_base = '&start_jd=' . $start_jd;
		$this->page = 'calendar';
	}
	
	function add_archive()
	{
		global $user, $db, $config;

		if (($config['archive_id'] != $this->forum_id) || !$this->forum_mode)
		{
			return;
		}
		
		$option_ary = array(
			'all'	=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all),
			'0'		=> array(
				'sql'	=> ' AND t.archive_forum_id = 0',
				'text'	=> $user->lang['ARCHIVE_UNKNOWN']),
			'line'	=> $this->line,
				);		
		
		$sql = 'SELECT forum_id, parent_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);	
		while($row = $db->sql_fetchrow($result))
		{
			$forum_ary[$row['forum_id']][] = $row['forum_id'];
			if ($row['parent_id'])
			{
				$forum_ary[$row['parent_id']][] = $row['forum_id'];
			}
		}
		
		$forum_select = $this->make_forum_select(false, false);
		
		if (sizeof($forum_select))
		{
			foreach ($forum_select as $select_option)
			{	
				$option_ary[$select_option['forum_id']] = array(
					'sql'	=> ' AND ' . $db->sql_in_set('t.archive_forum_id', $forum_ary[$select_option['forum_id']]),
					'text'	=> $select_option['padding'] . $select_option['forum_name'],
					);
			}
		}
		
		$archf = request_var('archf', 'all');
		
		$this->filters['archive'] = array(
			'en'		=> true,
			'sel'		=> $archf,			
			'u'			=> '&archf=' . $archf,
			'sql'		=> $option_ary[$archf]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'ARCHIVE',
			);
			
		return $forum_select;
	}
	
	function add_forums()
	{
		global $config, $db;
	
		if ($this->post_data['submit'])
		{
			$forum_list = ($this->post_data['forum_list']) ? $this->post_data['forum_list'] : array();
		}
		else
		{
			$forum_list = array();
		
			$sql = 'SELECT forum_id
				FROM ' . FORUMS_TABLE . ' 
				WHERE calendar_preset = 1';
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$forum_list[] = $row['forum_id'];
			}
			$db->sql_freeresult($result);
			
			if ($this->post_data['topic_id'])
			{
				$sql = 'SELECT forum_id, archive_forum_id
					FROM ' . TOPICS_TABLE . ' 
					WHERE topic_id = ' . $this->post_data['topic_id'];
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				$this->post_data['addf'] = ($row['forum_id'] == $config['archive_id']) ? $row['archive_forum_id'] : $row['forum_id'];
				$db->sql_freeresult($result);
			}
			
			if ($this->post_data['addf'] && !(in_array($this->post_data['addf'], $forum_list)))
			{
				$forum_list[] = $this->post_data['addf'];		
			}
		}		
		
		$omit_forum_ids = array(
			$config['archive_id'],
			$config['link_forum_id'],
			$config['circle_forum_id'],
			);			
	
		$forum_select = $this->make_forum_select($forum_list, false, $omit_forum_ids, false, true);
		
		$option_ary = array();

		if (sizeof($forum_select))
		{
			foreach ($forum_select as $select_option)
			{	
				$text = $select_option['forum_name'];
				$text = (strlen($text) > 50) ? substr($text, 0, 50) . '..' : $text;		
			
				$option_ary[$select_option['forum_id']] = array(
					'sql'	=> ' AND (t.forum_id = ' . $select_option['forum_id'] . ' 
						OR (t.forum_id = ' . $config['archive_id'] . ' AND t.archive_forum_id = ' . $select_option['forum_id'] . '))',
					'text'	=> $select_option['padding'] . $text,
					'sel'	=> in_array($select_option['forum_id'], $forum_list),
					'dis'	=> $select_option['disabled'],
					);

			}
		}

		if (sizeof($forum_list))
		{
			$sql_forums = ' AND (' . $db->sql_in_set('t.forum_id', $forum_list);
			$sql_forums .= ' OR (t.forum_id = ' . $config['archive_id'] . ' AND ';
			$sql_forums .= $db->sql_in_set('t.archive_forum_id', $forum_list) . '))'; 
		}
		else
		{
			$sql_forums = ' AND 1 = 0';
		}
		
		
		$this->filters['forums'] = array(
			'en'		=> true,
			'sel'		=> false,			
			'u'			=> '&fc[]=' . implode('&fc[]=', $forum_list),
			'sql'		=> $sql_forums,
			'options'	=> $option_ary,
			'template'	=> 'FORUM',
			'nocount'	=> true,
			);	
	}
	
	function add_prefix($prefix_group_id = 0)
	{
		global $db, $user, $cache;
		
		$prefixes = array();

		$sql = 'SELECT t.topic_prefix_id, t.topic_id ' . 
					$this->sql_from . '
					WHERE ' . $this->sql_base . $this->sql_pre . '
						AND t.topic_prefix_id <> 0
						ORDER BY t.topic_prefix_id ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{	  
			$prefixes[$row['topic_prefix_id']] = $row['topic_id'];
		}
		$db->sql_freeresult($result);

		if (!sizeof($prefixes))
		{
			return;
		}

		$prefix_names = $cache->obtain_prefixes();
		$prefix_groups = $cache->obtain_prefix_groups();
		$prefix_group = $prefix_groups[$prefix_group_id];
		
		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_prefix_id = 0',
				'text'	=> $user->lang['NO_PREFIX'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_prefix_id <> 0',
				'text'	=> $user->lang['WITH_PREFIX'],
				),
			'line'		=> $this->line,	
				);

		if (sizeof($prefixes))
		{
			foreach ($prefixes as $prefix_id => $topic_id)
			{
				$in_prefix_group = (sizeof($prefix_group)) ? (in_array($prefix_id, $prefix_group)) : false;
			
				$option_ary = array_merge($option_ary, array(
					$prefix_id => array(
						'sql'	=> ' AND t.topic_prefix_id = ' . $prefix_id,
						'text'	=> $prefix_names[$prefix_id] . (($in_prefix_group && !$this->calendar_mode) ? '*' : ''),
						)));
			}
		}
	
		$preff	= request_var('ttp', '0');

		$this->filters['prefix'] = array(
			'en'		=> true,
			'sel'		=> $preff,			
			'u'			=> '&ttp=' . $preff,
			'sql'		=> $option_ary[$preff]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'PREFIX',
			);

		return $prefix_names;
	}
	
	function add_calendar()
	{
		global $user, $db, $config;
		
		if (!$config['calendar_enable_filter'] || !$config['calendar_enable'])
		{
			return;
		}

		$sql = 'SELECT COUNT(t.topic_id) AS num
			FROM ' . TOPICS_TABLE . ' t
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.calendar_period_num <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.calendar_period_num = 0',
				'text'	=> $user->lang['NO_CALENDAR_TAG'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.calendar_period_num <> 0',
				'text'	=> $user->lang['WITH_CALENDAR_TAG'],
				));

		$sql = 'SELECT COUNT(t.topic_id) AS num, 
					t.calendar_period_num
			FROM ' . TOPICS_TABLE . ' t 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.calendar_period_num <> 0
				GROUP BY t.calendar_period_num ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{

			$num_periods = $row['calendar_period_num'];		
			
			$text = ($num_periods == 1) ? $user->lang['ONE_CALENDAR_TAG'] : sprintf($user->lang['MORE_CALENDAR_TAGS'], $num_periods);
			
			$option_ary = array_merge($option_ary, array(
				'n' . $num_periods	=> array(
					'sql'	=> ' AND t.calendar_period_num = ' . $num_periods,
					'text'	=> $text
				)));
		}	
		$db->sql_freeresult($result);
		
		$option_ary = array_merge($option_ary, array(
			'line'	=> $this->line,
			));
			
		$years = array();	
			
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.calendar_min_year
			FROM ' . TOPICS_TABLE . ' t 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.calendar_period_num <> 0 
				GROUP BY t.calendar_min_year ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$years[$row['calendar_min_year']] = $row['num'];
		}
		
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.calendar_max_year
			FROM ' . TOPICS_TABLE . ' t 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.calendar_period_num <> 0 
				GROUP BY t.calendar_max_year ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$years[$row['calendar_max_year']] = $row['num'];
		}
		
		$db->sql_freeresult($result);		
		
		foreach($years as $year => $num)
		{
			$option_ary = array_merge($option_ary, array(
				'y' . $year	=> array(
					'sql'	=> ' AND (t.calendar_min_year = ' . $year . ' OR t.calendar_max_year = ' . $year . ')',
					'text'	=> $year,
				)));	
		}
		
		$calf = request_var('calf', '0');

		$this->filters['calendar'] = array(
			'en'		=> true,
			'sel'		=> $calf,			
			'u'			=> '&calf=' . $calf,
			'sql'		=> $option_ary[$calf]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'CALENDAR',
			);
	}
	
	function add_location($topic_locations = false)
	{
		global $user, $db, $config;
		
		if (!$topic_locations || !$config['location_tags_enable'])
		{
			return;
		}

		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.location_num = 0',
				'text'	=> $user->lang['NO_LOCATION_TAG'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.location_num <> 0',
				'text'	=> $user->lang['WITH_LOCATION_TAG'],
				));

		$sql = 'SELECT COUNT(t.topic_id) AS num, 
					t.location_num ' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0
				GROUP BY t.location_num ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$num_locations = $row['location_num'];
			$text = ($num_locations == 1) ? $user->lang['ONE_LOCATION_TAG'] : sprintf($user->lang['MORE_LOCATION_TAGS'], $num_locations);
			
			$option_ary = array_merge($option_ary, array(
				'n' . $num_locations	=> array(
					'sql'	=> ' AND t.location_num = ' . $num_locations,
					'text'	=> $text
				)));
		}	
		$db->sql_freeresult($result);
		
		$option_ary = array_merge($option_ary, array(
			'line'	=> $this->line,
			));
			
		$locations = array();	
			
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.location_a1 ' . 
			$this->sql_from . ' 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0
				GROUP BY t.location_a1 ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$locations[$row['location_a1']] = $row['num'];
		}
		
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.location_a2 ' .
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0 
				AND t.location_a2 <> 0
				GROUP BY t.location_a2 ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$locations[$row['location_a2']] = $row['num'];
		}
		
		$db->sql_freeresult($result);		
		
		foreach($locations as $location_id => $num)
		{
			if (!$location_id)
			{
				continue;
			}
		
			$option_ary = array_merge($option_ary, array(
				$location_id	=> array(
					'sql'	=> ' AND (t.location_a1 = ' . $location_id . ' OR t.location_a2 = ' . $location_id . ')',
					'text'	=> $topic_locations->locations[$location_id],
				)));	
		}
		
		$locf = request_var('locf', '0');

		$this->filters['location'] = array(
			'en'		=> true,
			'sel'		=> $locf,			
			'u'			=> '&locf=' . $locf,
			'sql'		=> $option_ary[$locf]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LOCATION',
			);
	}	

	function add_image_thumbnail()
	{
		global $user, $db, $config;
		
		if ($this->forum_id != $config['link_forum_id'] && $this->forum_id != $config['circle_forum_id'])
		{
			return false;
		}
		
		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_thumbnail_ext <> \'\'';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_thumbnail_ext = \'\'',
				'text'	=> $user->lang['NO_IMAGE_THUMBNAIL'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_thumbnail_ext <> \'\'',
				'text'	=> $user->lang['WITH_IMAGE_THUMBNAIL'],
				));
					
		$image_thumbnail = request_var('image_thumbnail', '0');

		$this->filters['image_thumbnail'] = array(
			'en'		=> true,
			'sel'		=> $image_thumbnail,			
			'u'			=> '&image_thumbnail=' . $image_thumbnail,
			'sql'		=> $option_ary[$image_thumbnail]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'IMAGE_THUMBNAIL',
			);
	}

	
	function add_link_status()
	{
		global $user, $db, $config;
		
		if ($this->forum_id != $config['link_forum_id'])
		{
			return false;
		}
		
		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_status_id <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_link_status_id = 0',
				'text'	=> $user->lang['NO_LINK_STATUS'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_link_status_id <> 0',
				'text'	=> $user->lang['WITH_LINK_STATUS'],
				),
			'line'	=> $this->line,					
			topic_link::LINK_NEW => array(
				'sql'	=> ' AND t.topic_link_status_id = ' . topic_link::LINK_NEW,
				'text'	=> $user->lang['LINK_NEW'],
				),
			topic_link::LINK_OBSOLETE	=>	array(
				'sql'	=> ' AND t.topic_link_status_id = ' . topic_link::LINK_OBSOLETE,
				'text'	=> $user->lang['LINK_OBSOLETE'],
				),
			topic_link::LINK_BROKEN => array(
				'sql'	=> ' AND t.topic_link_status_id = ' . topic_link::LINK_BROKEN,
				'text'	=> $user->lang['LINK_BROKEN'],
				));	
					
		$link_status = request_var('link_status', '0');

		$this->filters['link_status'] = array(
			'en'		=> true,
			'sel'		=> $link_status,			
			'u'			=> '&link_status=' . $link_status,
			'sql'		=> $option_ary[$link_status]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LINK_STATUS',
			);
	}	
	

	function add_link_type($link_types)
	{
		global $user, $db, $config;
		
		if (!$link_types || ($this->forum_id != $config['link_forum_id']))
		{
			return false;
		}
		
		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_type_id <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_link_type_id = 0',
				'text'	=> $user->lang['NO_LINK_TYPE'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_link_type_id <> 0',
				'text'	=> $user->lang['WITH_LINK_TYPE'],
				),
			'line'	=> $this->line,		
				);	
			
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.topic_link_type_id ' . 
			$this->sql_from . ' 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_type_id <> 0
				GROUP BY t.topic_link_type_id ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$option_ary = array_merge($option_ary, array(
				$row['topic_link_type_id']	=> array(
					'sql'	=> ' AND (t.topic_link_type_id = ' . $row['topic_link_type_id'] . ')',
					'text'	=> $link_types[$row['topic_link_type_id']],
				)));			
		}
		
		$db->sql_freeresult($result);
		
		$link_type = request_var('link_type', '0');

		$this->filters['link_type'] = array(
			'en'		=> true,
			'sel'		=> $link_type,			
			'u'			=> '&link_type=' . $link_type,
			'sql'		=> $option_ary[$link_type]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LINK_TYPE',
			);
	}	
	
	function add_link_source($link_sources)
	{
		global $user, $db, $config;
		
		if (!$link_sources || ($this->forum_id != $config['link_forum_id']))
		{
			return false;
		}
		
		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_source_id <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_link_source_id = 0',
				'text'	=> $user->lang['NO_LINK_SOURCE'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_link_source_id <> 0',
				'text'	=> $user->lang['WITH_LINK_SOURCE'],
				),
			'line'	=> $this->line,		
				);	
			
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.topic_link_source_id ' . 
			$this->sql_from . ' 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_source_id <> 0
				GROUP BY t.topic_link_source_id ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$option_ary = array_merge($option_ary, array(
				$row['topic_link_source_id']	=> array(
					'sql'	=> ' AND (t.topic_link_source_id = ' . $row['topic_link_source_id'] . ')',
					'text'	=> $link_sources[$row['topic_link_source_id']],
				)));	
		}
		
		$db->sql_freeresult($result);
		
		$link_source = request_var('link_source', '0');

		$this->filters['link_source'] = array(
			'en'		=> true,
			'sel'		=> $link_source,			
			'u'			=> '&link_source=' . $link_source,
			'sql'		=> $option_ary[$link_source]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LINK_SOURCE',
			);
	}	

	function add_link_forum()
	{
		global $user, $db, $config;
		
		if ($this->forum_id != $config['link_forum_id'])
		{
			return false;
		}
		
		$sql = 'SELECT COUNT(t.topic_id) AS num' . 
			$this->sql_from . '
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_link_forum_id <> 0';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'all'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'no'	=> array(
				'sql'	=> ' AND t.topic_link_forum_id = 0',
				'text'	=> $user->lang['NO_LINK_FORUM'],
				),
			'with'	=>	array(
				'sql'	=> ' AND t.topic_link_forum_id <> 0',
				'text'	=> $user->lang['WITH_LINK_FORUM'],
				),
			'line'	=> $this->line,		
				);	

		$sql = 'SELECT forum_id, parent_id
			FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);	
		while($row = $db->sql_fetchrow($result))
		{
			$forum_ary[$row['forum_id']][] = $row['forum_id'];
			if ($row['parent_id'])
			{
				$forum_ary[$row['parent_id']][] = $row['forum_id'];
			}
		}
		
		$forum_select = $this->make_forum_select(false, false);
		
		if (sizeof($forum_select))
		{
			foreach ($forum_select as $select_option)
			{	
				$option_ary[$select_option['forum_id']] = array(
					'sql'	=> ' AND ' . $db->sql_in_set('t.topic_link_forum_id', $forum_ary[$select_option['forum_id']]),
					'text'	=> $select_option['padding'] . $select_option['forum_name'],
					);

			}
		}
		
		$link_forum = request_var('link_forum', 'all');
		
		$this->filters['archive'] = array(
			'en'		=> true,
			'sel'		=> $link_forum,			
			'u'			=> '&link_forum=' . $link_forum,
			'sql'		=> $option_ary[$link_forum]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LINK_FORUM',
			);
	}	
	
	
	function add_circle()
	{
		global $user, $db, $config;
		
		if (!$config['circle_forum_id'])
		{
			return;
		}

		$sql = 'SELECT COUNT(t.topic_id) AS num
			FROM ' . TOPICS_TABLE . ' tc 
			LEFT JOIN ' . USERS_CIRCLES_TABLE . ' uc ON (uc.circle_id = tc.topic_id)
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_circle_id = uc.circle_id)
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.topic_circle_id <> 0
				AND uc.user_id = ' . $user_id . '
			GROUP BY circle_id ASC';
		$result = $db->sql_query($sql);
		$count = (int) $db->sql_fetchfield('num');
		$db->sql_freeresult($result);					
				
		if (!$count)
		{
			return;
		}

		$option_ary = array(
			'0'		=> array(
				'sql'	=> '',
				'text'	=> $this->lang_all,
				),
			'out'	=> array(
				'sql'	=> ' AND t.topic_circle_id = 0',
				'text'	=> $user->lang['OUTSIDE_CIRCLE'],
				),
			'in'	=>	array(
				'sql'	=> ' AND t.topic_circle_id <> 0',
				'text'	=> $user->lang['INSIDE_CIRCLE'],
			'line'	=> $this->line,
				));


		
		$option_ary = array_merge($option_ary, array(
			'line'	=> $this->line,
			));
			
		$locations = array();	
			
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.location_a1
			FROM ' . TOPICS_TABLE . ' t 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0
				GROUP BY t.location_a1 ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$locations[$row['location_a1']] = $row['num'];
		}
		
		$db->sql_freeresult($result);
		
		$sql = 'SELECT COUNT(t.topic_id) AS num,
					t.location_a2
			FROM ' . TOPICS_TABLE . ' t 
			WHERE ' . $this->sql_base . $this->sql_pre . '
				AND t.location_num <> 0 
				AND t.location_a2 <> 0
				GROUP BY t.location_a2 ASC';
		$result = $db->sql_query($sql);
		
		while($row = $db->sql_fetchrow($result))
		{
			$locations[$row['location_a2']] = $row['num'];
		}
		
		$db->sql_freeresult($result);		
		
		foreach($locations as $location_id => $num)
		{
			if (!$location_id)
			{
				continue;
			}
		
			$option_ary = array_merge($option_ary, array(
				$location_id	=> array(
					'sql'	=> ' AND (t.location_a1 = ' . $location_id . ' OR t.location_a2 = ' . $location_id . ')',
					'text'	=> $topic_locations->locations[$location_id],
				)));	
		}
		
		$locf = request_var('locf', '0');

		$this->filters['location'] = array(
			'en'		=> true,
			'sel'		=> $locf,			
			'u'			=> '&locf=' . $locf,
			'sql'		=> $option_ary[$locf]['sql'],
			'options'	=> $option_ary,
			'template'	=> 'LOCATION',
			);
	}

// 

	function make_forum_select($select_id = false, $ignore_id = false, $omit_id = false, $ignore_empty = false, $ignore_cat = false)
	{
		global $db, $user, $auth;

		$sql = 'SELECT forum_id, forum_name, parent_id, forum_type, forum_flags, forum_options, left_id, right_id, forum_topics
			FROM ' . FORUMS_TABLE . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql, 600);

		$right = 0;
		$padding_store = array('0' => '');
		$padding = '';
		$forum_list = array();

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['left_id'] < $right)
			{
				$padding .= '&nbsp; &nbsp;';
				$padding_store[$row['parent_id']] = $padding;
			}
			else if ($row['left_id'] > $right + 1)
			{
				$padding = (isset($padding_store[$row['parent_id']])) ? $padding_store[$row['parent_id']] : '';
			}

			$right = $row['right_id'];
			$disabled = false;

			if (!$auth->acl_gets(array('f_list', 'a_forum', 'a_forumadd', 'a_forumdel'), $row['forum_id']))
			{
				continue;
			}

			if (((is_array($ignore_id) && in_array($row['forum_id'], $ignore_id)) || $row['forum_id'] == $ignore_id)
				|| ($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']))
				|| ($row['forum_type'] != FORUM_POST)
				|| ($ignore_empty && $row['forum_topics'])
				|| ($row['forum_type'] == FORUM_CAT && $ignore_cat))
			{
				$disabled = true;
			}
			
			if ((is_array($omit_id)) ? ((in_array($row['forum_id'], $omit_id)) ? true : false) : (($row['forum_id'] == $omit_id) ? true : false))
			{
				continue;
			}

			$selected = (is_array($select_id)) ? ((in_array($row['forum_id'], $select_id)) ? true : false) : (($row['forum_id'] == $select_id) ? true : false);
			$forum_list[$row['forum_id']] = array_merge(array('padding' => $padding, 'selected' => ($selected && !$disabled), 'disabled' => $disabled), $row);

		}
		$db->sql_freeresult($result);
		unset($padding_store);

		return $forum_list;
	}

//
	
	function gen_sql()
	{
		if (sizeof($this->filters))
		{
			foreach($this->filters as $filter => $f_ary)
			{			
				$this->sql_all_ary[$filter] = $f_ary['sql'];
			}
			
			$this->sql_all = implode('', $this->sql_all_ary);
		}
		
		return $this->sql_all;
	}
	
	function get_sql()
	{
		return $this->sql_all;
	}
	
	function get_sql_pre()
	{
		return $this->sql_pre;
	}
	
	function get_sql_base()
	{
		return $this->sql_base;	
	}

	function set_sql_suf($sql_suf)
	{
		$this->sql_suf = $sql_suf;
	}	
	
	function gen_u()
	{
		if (sizeof($this->filters))
		{
			foreach($this->filters as $filter => $f_ary)
			{			
				$this->u_all_ary[$filter] = $f_ary['u'];
			}
			
			$this->u_all = implode('', $this->u_all_ary);
		}
		
		return $this->u_all;
	}	
	
	function get_u()
	{
		return $this->u_all;
	}	

	function to_template()
	{
		global $db, $template, $user, $config, $phpbb_root_path, $phpEx;
		
		if (sizeof($this->filters))
		{
			foreach($this->filters as $filter => $f_ary)
			{
				$options = '';
				
				$sql_copy_ary = $this->sql_all_ary;
				$sql_copy_ary['sql_add'] = $this->sql_pre . $this->sql_suf;
				
				foreach($f_ary['options'] as $val => $option)
				{
					$sql_copy_ary[$filter] = $option['sql'];
					$sql_where = implode('', $sql_copy_ary);

					$sql = 'SELECT COUNT(DISTINCT t.topic_id) AS num_topics ' . 
								$this->sql_from . '
								WHERE ' . $this->sql_base . $sql_where;
					$result = $db->sql_query($sql);
					$count = (int) $db->sql_fetchfield('num_topics');
					$db->sql_freeresult($result);

					$disable = ($f_ary['nocount']) ? $option['dis'] : !$count;
					
					$options .= '<option';
					$options .= ' value="' . $val . '"';
					$options .= ($disable) ? ' disabled="disabled"' : '';
					$options .= (((string) $f_ary['sel'] == (string) $val) || ($option['sel'] && !$option['dis'])) ? ' selected="selected"' : '';
					$options .= '>' . $option['text'];
					$options .= ($count) ? ' (' . $count . ')' : '';
					$options .= '</option>';	
				}			

				$template->assign_vars(array(
					$f_ary['template'] . '_FILTER_OPTIONS'	=> $options,		
					));	
			}

				
			if ($config['hide_filters_enable'])
			{
				$filters_en = request_var('filt', '');

				switch ($filters_en)
				{
					case 'en':

						$user->hide_filters = 0;

						$sql = 'UPDATE ' . USERS_TABLE . '
									SET user_hide_filters = ' . $user->hide_filters . '
									WHERE user_id = ' . $user->data['user_id'];
						$db->sql_query($sql);
						
						$hide_filters = false;
						
						break;
						
					case 'dis':
					
						$user->hide_filters = 1;
						
						$sql = 'UPDATE ' . USERS_TABLE . '
									SET user_hide_filters = ' . $user->hide_filters . '
									WHERE user_id = ' . $user->data['user_id'];
						$db->sql_query($sql);

						$hide_filters = true;

						break;
						
					default:
					
						$hide_filters = $user->hide_filters;
				}
				
				$u_hide_filters = '&filt=';
				$u_hide_filters .= ($hide_filters) ? 'en' : 'dis';
			}

			$template->assign_vars(array(
				'S_FILTERBOX'				=> true,
				'S_HIDE_FILTERS'			=> $hide_filters,
				'S_HIDE_FILTERS_ENABLE'		=> $config['hide_filters_enable'],
				'S_HIDE_FILTERS_ACTION'		=> append_sid($phpbb_root_path . $this->page . '.' . $phpEx) . $this->u_base . $u_hide_filters,
				'S_RESET_ACTION'			=> append_sid($phpbb_root_path . $this->page . '.' . $phpEx) . $this->u_base,	
				));				

			return true;
		}
		
		return false;
	}
	
	
	
	function gen_limit_days($sql_approved)
	{
		global $db, $user; 

		$limit_days = array(
			0 => $this->lang_all, 
			1 => $user->lang['1_DAY'], 
			7 => $user->lang['7_DAYS'], 
			14 => $user->lang['2_WEEKS'], 
			30 => $user->lang['1_MONTH'], 
			90 => $user->lang['3_MONTHS'], 
			180 => $user->lang['6_MONTHS'], 
			365 => $user->lang['1_YEAR'], 
			730 => $user->lang['2_YEARS']);

		foreach ($limit_days as $days => &$text)
		{
			if ($days)
			{
				$min_post_time = time() - ($days * 86400);
				$sql_limit_time = ' AND t.topic_last_post_time >= ' . $min_post_time;
			}
			else
			{
				$sql_limit_time = '';
			}

			$sql = 'SELECT COUNT(t.topic_id) AS result_count 
				FROM ' . TOPICS_TABLE . ' t
				WHERE ' . $this->sql_base . 
					$this->sql_pre . 
					$this->sql_all . 
					$sql_approved . 
					$sql_limit_time;

			$result = $db->sql_query($sql);
			$result_count = (int) $db->sql_fetchfield('result_count');
			$db->sql_freeresult($result);
			
			$text .= ($result_count) ? ' (' . $result_count . ')' : ' -';
		}
		
		return $limit_days;
	}
}

// filter mod end

// link mod // circle mod begin

class topic_thumbnail
{
	var $topic_id;
	var $max_width = 200;
	var $thumb_filename;
	var $source_filename;
	var $extension;
	var $prefix = 'th_';
	var $dir = 'thumbnails/';
	
	
	function topic_thumbnail($topic_id = 0)
	{
		$this->topic_id = $topic_id;
	}
	
	
		
	function set_max_width($max_width)
	{
		$this->max_width = $max_width;
	}	
		
	function get_img_size($width, $height)
	{
		global $config;

		$max_width = ($this->max_width) ? $this->max_width : 250;

		if ($width > $height)
		{
			return array(
				round($width * ($max_width / $width)),
				round($height * ($max_width / $width))
			);
		}
		else
		{
			return array(
				round($width * ($max_width / $height)),
				round($height * ($max_width / $height))
			);
		}
	}

	function get_source_filename()
	{
		global $db, $phpbb_root_path, $config;

		$sql = 'SELECT physical_filename, extension
			FROM ' . ATTACHMENTS_TABLE . '
			WHERE topic_id = ' . $this->topic_id . '
				AND is_orphan = 0
			LIMIT 0, 1';
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row)
		{
			$this->extension = strtolower(trim($row['extension']));
			$this->source_filename = $phpbb_root_path . $config['upload_path'] . '/' . utf8_basename($row['physical_filename']);	
		}
		
		return $this->source_filename;
	}
	
	function get_thumb_filename($topic_id_and_ext = false)
	{
		global $phpbb_root_path, $config;

		$topic_id_and_ext = ($topic_id_and_ext) ? $topic_id_and_ext : $this->topic_id . '.' . $this->extension;
		
		return ($this->thumb_filename = $phpbb_root_path . 'images/thumbnails/' . $this->prefix . $topic_id_and_ext);
	}
	
	function create()
	{
		global $config, $db;

		if (!$this->get_source_filename() || !$this->get_thumb_filename())
		{
			return false;
		}
			
		$min_filesize = (int) $config['img_min_thumb_filesize'];
		$img_filesize = (file_exists($this->source_filename)) ? @filesize($this->source_filename) : false;

		if (!$img_filesize || $img_filesize <= $min_filesize)
		{
			return false;
		}

		$dimension = @getimagesize($this->source_filename);

		if ($dimension === false)
		{
			return false;
		}

		list($width, $height, $type, ) = $dimension;

		if (empty($width) || empty($height))
		{
			return false;
		}

		list($new_width, $new_height) = $this->get_img_size($width, $height);

		// Do not create a thumbnail if the resulting width/height is bigger than the original one
		if ($new_width >= $width && $new_height >= $height)
		{
			return false;
		}

		$used_imagick = false;

		// Only use imagemagick if defined and the passthru function not disabled
		if ($config['img_imagick'] && function_exists('passthru'))
		{
			if (substr($config['img_imagick'], -1) !== '/')
			{
				$config['img_imagick'] .= '/';
			}

			@passthru(escapeshellcmd($config['img_imagick']) . 'convert' . ((defined('PHP_OS') && preg_match('#^win#i', PHP_OS)) ? '.exe' : '') . ' -quality 85 -geometry ' . $new_width . 'x' . $new_height . ' "' . str_replace('\\', '/', $this->source_filename) . '" "' . str_replace('\\', '/', $this->thumb_filename) . '"');

			if (file_exists($this->thumb_filename))
			{
				$used_imagick = true;
			}
		}

		if (!$used_imagick)
		{
			$type = get_supported_image_types($type);

			if ($type['gd'])
			{
				if ($type['format'] === false)
				{
					return false;
				}

				switch ($type['format'])
				{
					case IMG_GIF:
						$image = @imagecreatefromgif($this->source_filename);
					break;

					case IMG_JPG:
						@ini_set('gd.jpeg_ignore_warning', 1);
						$image = @imagecreatefromjpeg($this->source_filename);
					break;

					case IMG_PNG:
						$image = @imagecreatefrompng($this->source_filename);
					break;

					case IMG_WBMP:
						$image = @imagecreatefromwbmp($this->source_filename);
					break;
				}

				if (empty($image))
				{
					return false;
				}

				if ($type['version'] == 1)
				{
					$new_image = imagecreate($new_width, $new_height);

					if ($new_image === false)
					{
						return false;
					}

					imagecopyresized($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				}
				else
				{
					$new_image = imagecreatetruecolor($new_width, $new_height);

					if ($new_image === false)
					{
						return false;
					}

					// Preserve alpha transparency (png for example)
					@imagealphablending($new_image, false);
					@imagesavealpha($new_image, true);

					imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
				}

				// If we are in safe mode create the destination file prior to using the gd functions to circumvent a PHP bug
				if (@ini_get('safe_mode') || @strtolower(ini_get('safe_mode')) == 'on')
				{
					@touch($this->thumb_filename);
				}

				switch ($type['format'])
				{
					case IMG_GIF:
						imagegif($new_image, $this->thumb_filename);
					break;

					case IMG_JPG:
						imagejpeg($new_image, $this->thumb_filename, 90);
					break;

					case IMG_PNG:
						imagepng($new_image, $this->thumb_filename);
					break;

					case IMG_WBMP:
						imagewbmp($new_image, $this->thumb_filename);
					break;
				}

				imagedestroy($new_image);
			}
			else
			{
				return false;
			}
		}

		if (!file_exists($this->thumb_filename))
		{
			return false;
		}

//		phpbb_chmod($this->thumb_filename, CHMOD_READ | CHMOD_WRITE);

		$sql = 'UPDATE ' . TOPICS_TABLE . ' 
				SET topic_thumbnail_ext = \'' . $this->extension . '\' 
			WHERE topic_id = ' . $this->topic_id;
		$db->sql_query($sql);		
		
		return true;
	}
	
	function read_extension()
	{
		global $db;
		
		$sql = 'SELECT topic_thumbnail_ext
			FROM ' . TOPICS_TABLE . '
			WHERE topic_id = ' . $this->topic_id;
		$result = $db->sql_query($sql);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		if ($row)
		{
			$this->extension = strtolower(trim($row['topic_thumbnail_ext']));	
		}

		return $this->extension;
	}
	
	function set_extension($extension)
	{
		$this->extension = $extension;
	}
	
	function get_img()
	{	
		if ($file = $this->get_thumb_filename())
		{
			return '<img src="' . $file . '" alt="" />';
		}
		else
		{
			return false;
		}
	}
	
	function clear()
	{
		global $db;
		
		$sql = 'UPDATE ' . TOPICS_TABLE . ' 
				SET topic_thumbnail_ext = \'\'
			WHERE topic_id = ' . $this->topic_id;
		$db->sql_query($sql);		
	}
}

class topic_list_thumbnail
{
	var $topic_thumbnail;

	function topic_list_thumbnail()   // only used for getting the filename
	{
		$this->topic_thumbnail = new topic_thumbnail();
	}

	function get_img($topic_id, $extension)
	{	
	

		if (($file = $this->topic_thumbnail->get_thumb_filename($topic_id . '.' . $extension)) && $extension)
		{		
			return '<img src="' . $file . '" alt="" />';
		}
		else
		{
			return false;
		}
	}
}

// link mod // circle mod end

// link mod begin

class topic_link
{
	const LINK_NEW = 1;
	const LINK_OBSOLETE = 2;
	const LINK_BROKEN = 3;
	
	var	$status = array();
	var $type = array();
	var $source = array();	
	var $forum = array();
	var $prefix = array();
	
	var $status_str = '';
	var $type_str = '';
	var $source_str = '';
	var $forum_str = '';
	var $prefix_str = '';
	
	var $location = array();
	var $image = array();
	
	var $status_class = array();	
	var $forum_list = array();

	var $line = array('line' => '--------');

	
	function topic_link($cache_link_source_type = false, $forum_select = false, $preselect = false, $prefixes = array())
	{
		global $cache, $user, $db, $config;

		$this->status = array(			
			self::LINK_NEW		=> $user->lang['LINK_NEW'],
			self::LINK_BROKEN	=> $user->lang['LINK_BROKEN'],
			self::LINK_OBSOLETE	=> $user->lang['LINK_OBSOLETE'],
			);

		$this->status_class = array(			
			self::LINK_NEW		=> 'link-new',
			self::LINK_BROKEN	=> 'link-broken',
			self::LINK_OBSOLETE	=> 'link-obsolete',
			);	

		if ($cache_link_source_type)
		{
			$link_filters = $cache->obtain_link_filters();
			$this->type = $link_filters['type'];					
			$this->source = $link_filters['source'];
		}


		
		if ($forum_select)
		{
			$this->make_forum_select();
		}
		
		if ($preselect)
		{
			$sql = 'SELECT pg.prefix_id
					FROM ' . TTP_PREFIXES_GROUPS_TABLE . ' pg
					LEFT JOIN ' . FORUMS_TABLE . ' f ON (f.prefix_group_id = pg.group_id)
					WHERE f.forum_id = ' . $config['link_forum_id'] . '
					ORDER BY pg.prefix_id ASC';
			$result = $db->sql_query($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$this->prefix[$row['prefix_id']] = $prefixes[$row['prefix_id']];
			}
			$db->sql_freeresult($result);			
		
			$this->status_str = implode('_', array_keys($this->status));
			$this->type_str = implode('_', array_keys($this->type));		
			$this->source_str = implode('_', array_keys($this->source));
			$this->forum_str = implode('_', array_keys($this->forum));
			$this->prefix_str = implode('_', array_keys($this->prefix));			
		
			$this->location = array(
				'no'	=> $user->lang['WITHOUT_LOCATION'],				
				'with'	=> $user->lang['WITH_LOCATION'],	
				'cont'	=> $user->lang['MATCH_CONTINENT'],		
				'full'	=> $user->lang['MATCH_FULL_LOCATION'],
				);
			$this->image = array(
				'no'	=> $user->lang['WITHOUT_IMAGE'],			
				'with'	=> $user->lang['WITH_IMAGE'],
				);
			$this->status = array(
				'no'	=> $user->lang['WITHOUT_STATUS'],
				'with'	=> $user->lang['WITH_STATUS'],
				) + $this->line + $this->status;
			$this->type = array(
				'no'	=> $user->lang['WITHOUT_TYPE'],
				'with'	=> $user->lang['WITH_TYPE'],
				) + $this->line + $this->type;				
			$this->source = array(
				'no'	=> $user->lang['WITHOUT_SOURCE'],
				'with'	=> $user->lang['WITH_SOURCE'],
				) + $this->line + $this->source;					
			$this->forum = array(
				'no'	=> $user->lang['NO_LINK_FORUM'],
				'with'	=> $user->lang['WITH_LINK_FORUM'],
				'this'	=> $user->lang['LINK_THIS_FORUM'],
				) + $this->line + $this->forum;
			$this->prefix = array(
				'no'	=> $user->lang['NO_PREFIX'],
				'with'	=> $user->lang['WITH_PREFIX'],
				'this'	=> $user->lang['LINK_THIS_PREFIX'],
				) + $this->line + $this->prefix;					
		}
	}
	
	function preselect_ary($loc_a_select)
	{
		$this->location += $this->line + $loc_a_select;
	
		$ary = array(
			'location'	=> $this->location,
			'image'		=> $this->image,
			'status'	=> $this->status,
			'type'		=> $this->type,
			'source'	=> $this->source,
			'forum'		=> $this->forum,
			'prefix'	=> $this->prefix,
			);
		return $ary;		
	}
	
	function tag_ary()
	{
		$ary = array(
			'status'	=> $this->status,
			'type'		=> $this->type,
			'source'	=> $this->source,
			'forum'		=> $this->forum,
			);
		return $ary;
	}
	
	function type_source_str($type_id, $source_id)
	{
		$str = $this->type[$type_id];
		$str .= ($type_id && $source_id) ? ', ' : '';
		$str .= $this->source[$source_id];
		return $str;
	}
	
	function forum_str($forum_id)
	{
		global $db, $user, $phpbb_root_path, $phpEx;
		
		if(!$forum_id)
		{
			return false;
		}
		
		$tag_str = $user->lang['RELATED_FORUM'] . ': <a href="';
		$tag_str .= append_sid($phpbb_root_path . 'viewforum.' . $phpEx, 'f=' . $forum_id) . '">';
		
		if (sizeof($this->forum_list))
		{
			return $tag_str . $this->forum_list[$forum_id]['forum_name'] . '</a>';
		}

		$sql = 'SELECT forum_name
			FROM ' . FORUMS_TABLE . '
			WHERE forum_id = ' . $forum_id;
		$result = $db->sql_query($sql, 600);
		$row = $db->sql_fetchrow($result);
		$db->sql_freeresult($result);

		return $tag_str . $row['forum_name'] . '</a>';
	}
	
	function make_forum_select()
	{
		global $db, $user, $auth;

		$sql = 'SELECT forum_id, 
					forum_name, 
					parent_id, 
					forum_type, 
					left_id, 
					right_id, 
					forum_topics
			FROM ' . FORUMS_TABLE . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql, 600);

		$right = 0;
		$padding_store = array('0' => '');
		$padding = '';

		while ($row = $db->sql_fetchrow($result))
		{
			if ($row['left_id'] < $right)
			{
				$padding .= '&nbsp; &nbsp;';
				$padding_store[$row['parent_id']] = $padding;
			}
			else if ($row['left_id'] > $right + 1)
			{
				$padding = (isset($padding_store[$row['parent_id']])) ? $padding_store[$row['parent_id']] : '';
			}

			$right = $row['right_id'];
			$disabled = false;

			if (!$auth->acl_gets(array('f_list', 'a_forum', 'a_forumadd', 'a_forumdel'), $row['forum_id']))
			{
				continue;
			}

			if (($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']))
				|| ($row['forum_type'] != FORUM_POST))
			{
				$disabled = true;
			}

			$this->forum_list[$row['forum_id']] = array_merge(array('padding' => $padding, 'disabled' => $disabled), $row);
			$this->forum[$row['forum_id']] = $padding . $row['forum_name'];
		}
		$db->sql_freeresult($result);
		unset($padding_store);

		return;
	}	
}			


// link mod end


?>