<?php


class calendar_pattern
{
	public $length;	
	public $ary;
	
	public static $keymap = array(		// keys that may be processed as a pattern 
		'class'				=> 0,
		'text'				=> 0,
		'title'				=> 0
		);	
		
	function __construct(
		$ary = array())
	{
		$this->ary = $ary;
		$this->length = sizeof($ary);	
	}	
}


class calendar
{
	private static $post_data = array();

	private static $days_num = 28;
	private static $tables_num = 1;	
	private static $table_days_num = 28;
	
	private static $start_jd;
	private static $end_jd;
	private static $today_jd;
	private static $today;
	private static $show_today = 0;
	
	private static $start_week = 0;
	private static $first_monday = 0;
	
	private static $text_char_per_col = 4.7;
	private static $text_limit_one_col = 2;
	private static $text_min_span_ratio = 0.25;
	private static $text_min_span = 7;

	private static $multi_row_ary = array();
	private static $tables = array();

	private static $props = array(
		'type'				=> self::DAYS_TYPE,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 20,
	//	'display'			=> true
		);
	private static $row_props = array();
	private static $props_keymap = array(   
		'class'				=> 0,
		'today_class'		=> 0,
		'text'				=> 0,
		'title'				=> 0,
		'onclick'			=> 0,
		'colspan'			=> 0,
		'rowspan'			=> 0,
		'type'				=> 0,
		'min_evt_rows'		=> 0,
		'max_evt_rows'		=> 0,
		'display'			=> 0
		);
	
	//
	private static $row = -1;
	private static $col = 0;
	private static $table_col = 0;
	private static $table_write;
	private static $table_index = 0;
	
	private static $ch_row = 'r';
	private static $ch_col = 'c';
	private static $ch_table = 't';

	// classes for every col
	private static $col_class_ary = array();
	
	// arrays that keep track of grouped (per col or grp) calendar cell properties
	private static $msvr_evt_ary = array();
	
	// arrays to javascript.
	private static $mouseover_evt_id_ary = array();
	private static $evt_topic_id_ary = array();
	
	// types of rows
	const TODAY_INDICATOR = 1;
	const TARGET_INDICATOR = 2;
	const TODAY_TARGET_INDICATOR = 3;
	const DAYS_TYPE = 10;
	const GREG_MONTHDAYS = 20;
	const MOONPHASE = 30;
	const ISOWEEK = 40;
	const MOONPHASE_ISOWEEK = 41;
	const PERIODS_TYPE = 100;
	const GREG_MONTH = 101;
	const GREG_YEAR = 102;
	const GREG_MONTH_YEAR = 103;
	const EVENTS_TYPE = 300;
	const BIRTHDAYS_TYPE = 400;
	
	// 
	const COL = 1;
	const ROW = 2;
	const EVT = 3;
	
	// 
	const F_FG_FORUM_ID = 0;
	const F_FG_GROUP_1_ID = 1;
	const F_FG_GROUP_2_ID = 2;
	const F_FG_SAVED = 3;
	
	//
	private static $row_type_ary = array();
	private static $cells = array();

	// moon
	private static $moon_phase_ary = array();
	private static $moon_unix_ary = array();
	private static $moon_class_ary = array('m0', 'm1', 'm2', 'm3');
	private static $moon_text_ary = array('CALENDAR_NEW_MOON', 'CALENDAR_FIRST_QUARTER_MOON', 'CALENDAR_FULL_MOON', 'CALENDAR_THIRD_QUARTER_MOON');
	private static $moon_timeformat = 'H:i';
	
	// events
	private static $cal_events = array();
	private static $prefix_list = array();
	
	// birthdays
	private static $birthdays = array();
	private static $birthday_row_count = 0;
	
	/**
	**
	**/
	
	public static function init($ary = array())
	{
		global $db, $template, $user, $phpEx, $config;
	
		foreach ($ary as $key => $val)
		{
			if (array_key_exists($key, self::$props_keymap))
			{
				self::$props[$key] = $val;
				continue;
			}
			
			if (property_exists(calendar, $key))
			{
				self::$$key = $val;
			}
		}	
		
		if (!isset(self::$start_jd))
		{
			return false;
		}
		
		self::$table_days_num = (int) floor(self::$days_num / self::$tables_num);
		self::$days_num = self::$table_days_num * self::$tables_num;
	
		$class = self::$props['class'];
		$today_class = self::$props['today_class'];
		
		self::$start_week = self::$start_jd % 7;
		self::$first_monday = 7 - (self::$start_week);
		self::$today = self::$today_jd - self::$start_jd;
		
		if ($class)
		{
			if ($class instanceof calendar_pattern)
			{
			
				if ($class->ary[0] instanceof calendar_pattern)
				{
					$countdown = 0;
				
					for ($col = 0; $col < self::$days_num; $col++)
					{
						if (!$countdown)
						{
							$unix = ((self::$start_jd + $col) - 2440588) * 86400;
							$month_num_days = (int) gmdate('t', $unix);
							$month_day = (int) gmdate('j', $unix);						
							$countdown = $month_num_days - $month_day + 1;
							$month = (int) gmdate('n', $unix) - 1;
							$year = (int) gmdate('Y', $unix);
							$val = $class->ary[($month + ($year *12)) % $class->length];					
						}
						
						self::$col_class_ary[$col] = $val->ary[abs((self::$start_week + $col) % $val->length)];					
						
						$countdown--;
					}
				}
				else
				{
					for ($col = 0; $col < self::$days_num; $col++)
					{
						self::$col_class_ary[$col] = $class->ary[abs((self::$start_week + $col) % $class->length)];
					}
				}
			}
			else
			{
				for ($col = 0; $col < self::$days_num; $col++)
				{
					self::$col_class_ary[$col] = $class;
				}				
			}
		}

		if (isset($today_class) && self::$today >= 0 && self::$today < self::$days_num)
		{
			self::$col_class_ary[self::$today] .= ' ' . $today_class;
		}

		unset(self::$props['class'], self::$props['today_class']);
		
		self::$end_jd = self::$start_jd + self::$days_num;
		
		self::find_moonphases();
		
		self::$text_min_span = floor(self::$table_days_num * self::$text_min_span_ratio);
		
	//
	// copy format from user_dateformat for moon_timeformat
		
		$dateformat = $user->data['user_dateformat'];
		$am_pm = '';
		$g_bool = is_int(strpos($dateformat, 'g'));
		
		if ($g_bool || is_int(strpos($dateformat, 'h')))
		{
			$hours = ($g_bool) ? 'g' : 'h';
			$am_pm = (is_int(strpos($dateformat, 'A'))) ? ' A' : ' a';
		}
		else
		{
			$hours = (is_int(strpos($dateformat, 'G'))) ? 'G' : 'H';
		}
		
		self::$moon_timeformat = $hours . ':i' . $am_pm;
			
	//
	//
	//
	//
	// get forum list
		
		$forum_list = array();
	
		if (self::$post_data['submit'])
		{
			$forum_list = $post_data['forum_list'];
		}
		else
		{
			$sql = 'SELECT forum_id
				FROM ' . FORUMS_TABLE . ' 
				WHERE calendar_preset = 1';
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$forum_list[] = $row['forum_id'];
			}
			$db->sql_freeresult($result);
			
			if (self::$post_data['topic_id'])
			{
				$sql = 'SELECT forum_id, archive_forum_id
					FROM ' . TOPICS_TABLE . ' 
					WHERE topic_id = ' . self::$post_data['topic_id'];
				$result = $db->sql_query($sql);
				$row = $db->sql_fetchrow($result);
				self::$post_data['addf'] = ($row['forum_id'] == $config['archive_id']) ? $row['archive_forum_id'] : $row['forum_id'];
				$db->sql_freeresult($result);
			}
			
			if (self::$post_data['addf'] && !(in_array(self::$post_data['addf'], $forum_list)))
			{
				$forum_list[] = self::$post_data['addf'];		
			}
		}
		
	//
	
		$ignore_forum_ids = array(
			$config['archive_id'],
			$config['link_forum_id'],
			$config['circle_forum_id'],
			);	

		$sql = 'SELECT forum_id
			FROM ' . FORUMS_TABLE . ' 
			WHERE forum_topics = 0';
		$result = $db->sql_query($sql);
		while($row = $db->sql_fetchrow($result))
		{
			$ignore_forum_ids[] = $row['forum_id'];
		
		}
		$db->sql_freeresult($result);		
	

		
		$forum_select = make_forum_select($forum_list, $ignore_forum_ids, false, true, true, false, true);
		
		$forum_select_options = '';
		
		if (sizeof($forum_select))
		{
			foreach ($forum_select as $select_option)
			{
				$forum_select_options .= '<option value="' . $select_option['forum_id'] . '"';
				$forum_select_options .= ($select_option['disabled']) ? ' disabled="disabled" class="disabled-option"' : '';
				$forum_select_options .= ($select_option['selected']) ? ' selected="selected"' : '';
				$forum_select_options .= '>' . $select_option['padding'] . $select_option['forum_name'];
				$forum_select_options .= ($calendar_forum[$select_option['forum_id']]['calendar_max_periods']) ? ' (' . $calendar_forum[$select_option['forum_id']]['calendar_max_periods'] . ')' : '';
				$forum_select_options .= '</option>';
	
			}
		}	

		$template->assign_vars(array(
			'S_CALENDAR_FORUMS_OPTIONS'	=> $forum_select_options,
			));
		
		
//////////////////////////////////


	
	// print view link
		
		$print_view = '&amp;start_jd=' . self::$start_jd;
		$print_view .= '&amp;show_today=0';
		$print_view .= (sizeof($forum_list)) ? '&amp;fc[]=' . implode('&amp;fc=', $forum_list) : '';

		$template->assign_vars(array(
			'U_PRINT_TOPIC'			=> append_sid($phpbb_root_path . 'calendar.'  . $phpEx, 'view=print' . $print_view),	
			));	

	// get calendar events
		
		$topic_list = $topic_id_listed = array();		

		$sql = 'SELECT c.*, t.forum_id, t.topic_title, 
					t.topic_approved, t.topic_reported, 
					t.topic_bg_color
				FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . CALENDAR_PERIODS_TABLE . ' c ON (t.topic_id = c.topic_id)
				WHERE ' . $db->sql_in_set('t.forum_id', $forum_list, false, true) . '
					AND (c.start_jd <= ' . self::$end_jd . ')
					AND (c.end_jd >= ' . self::$start_jd . ')
					AND t.topic_approved = 1';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			self::$cal_events[] = $row;
			if (!$topic_id_listed[$row['topic_id']])
			{
				$topic_id_listed[$row['topic_id']] = true;
				$topic_list[] = $row['topic_id'];	
			}
		}
		$db->sql_freeresult($result);

	// get calendar events from archived topics
		
		$sql = 'SELECT c.*, t.forum_id, t.topic_title, 
					t.topic_approved, t.topic_reported, 
					t.topic_bg_color
				FROM ' . TOPICS_TABLE . ' t
				LEFT JOIN ' . CALENDAR_PERIODS_TABLE . ' c ON (t.topic_id = c.topic_id)
				WHERE t.forum_id = ' . $config['archive_id'] . '
					AND ' . $db->sql_in_set('t.archive_forum_id', $forum_list, false, true) . '
					AND (c.start_jd <= ' . self::$end_jd . ')
					AND (c.end_jd >= ' . self::$start_jd . ')
					AND t.topic_approved = 1';
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			self::$cal_events[] = $row;
			if (!$topic_id_listed[$row['topic_id']])
			{
				$topic_id_listed[$row['topic_id']] = true;
				$topic_list[] = $row['topic_id'];	
			}
		}
		$db->sql_freeresult($result);	

	// get topic title prefixes
	
		$sql = 'SELECT t.topic_id, p.prefix_name
			FROM ' . TTP_PREFIXES_TABLE . ' p
			LEFT JOIN ' . TOPICS_TABLE . ' t ON (t.topic_prefix_id = p.prefix_id)
			WHERE ' . $db->sql_in_set('t.topic_id', $topic_list, false, true);
		$result = $db->sql_query($sql);
		while ($row = $db->sql_fetchrow($result))
		{
			self::$prefix_list[$row['topic_id']][] = $row['prefix_name'];
		}
		$db->sql_freeresult($result);
		
	// get birthdays

		if (self::$post_data['display_birthdays'])
		{
			$greg = cal_from_jd(self::$start_jd, CAL_GREGORIAN);
			$start_day = $greg['day'];
			$start_month = $greg['month'];
			$start_year = $greg['year'];
			$greg = cal_from_jd(self::$end_jd, CAL_GREGORIAN);
			$end_day = $greg['day'];
			$end_month = $greg['month'];
			$end_year = $greg['year'];

			if ($start_month == $end_month)
			{
				$sql_where = ' user_bday_day >= ' .  $start_day . ' AND user_bday_day <= ' . $end_day;
			}
			else
			{
				$days_in_start_month = cal_days_in_month(CAL_GREGORIAN, $start_month, $start_year);
				$sql_where = ' (user_bday_day >= ' . $start_day . ' AND user_bday_day <= ' . $days_in_start_month . ' AND user_bday_month = ' . $start_month . ')';

				$next_month = $start_month + 1;
				$next_month = ($next_month == 13) ? 1 : $next_month;  
				
				while ($next_month != $end_month)
				{
					$sql_where .= ' OR user_bday_month = ' . $next_month;
				
					$next_month++;
					$next_month = ($next_month == 13) ? 1 : $next_month;
				}

				$sql_where .= ' OR (user_bday_day <= ' . $end_day . ' AND user_bday_month = ' .  $end_month . ')';
			}
			
			$sql = 'SELECT user_id, username, user_bday_day, user_bday_month, user_bday_year
					FROM ' . USERS_TABLE . '
					WHERE ' . $sql_where;
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{
				$jd1 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $start_year);
				self::add_birthday($row['user_id'], $row['username'], $start_year - $row['user_bday_year'], $jd1);				
				
				if ($start_year != $end_year)
				{
					$jd2 = gregoriantojd($row['user_bday_month'], $row['user_bday_day'], $end_year);
					self::add_birthday($row['user_id'], $row['username'], $end_year - $row['user_bday_year'], $jd2);
				}
			}
			$db->sql_freeresult($result);		
		}		
	}
	
	/**
	**
	**/
	
	private static function add_birthday($user_id, $username, $age, $jd)
	{
		if ($jd < self::$start_jd && $jd > self::$end_jd)
		{	
			return;
		}
		
		for ($r = 0; $r < 10; $r++)
		{
			if (!isset(self::$birthdays[$r][$jd - self::$start_jd]))
			{
				self::$birthdays[$r][$jd - self::$start_jd] = array(
					'user_id'	=> $user_id,
					'username'	=> $username,
					'age'		=> $age,
					);				
				
				self::$birthday_row_count = ($birthday_row_count > ($r + 1)) ? $birthday_row_count : ($r + 1); 

				break;
			}
		}	
	}
	

	/**
	**
	**/
	
	public static function init_row($ary = array())
	{
		global $user;
	
		self::$row_props = array();
		self::$row++;
		
		foreach ($ary as $key => $val)
		{
			if (array_key_exists($key, self::$props_keymap))
			{
				self::$row_props[$key] = $val;
			}
		}
		
		$today_class = self::$row_props['today_class'];
		unset(self::$row_props['today_class']);
	
		$row_props = self::$row_props = array_merge(self::$props, self::$row_props);

		if (($row_props['type'] == self::BIRTHDAYS_TYPE) && !sizeof(self::$birthdays) && !$birthday_row_count)
		{
			self::$row--;
			return;
		}

		$type = self::$row_type_ary[self::$row] = $row_props['type'];
		
		//
		switch ($type)
		{
			case self::GREG_MONTHDAYS: 
			
				$class = $row_props['class'];
			
				if ($class instanceof calendar_pattern && $class->ary[0] instanceof calendar_pattern)
				{
					$countdown = 0;
				
					for ($col = 0; $col < self::$days_num; $col++)
					{
						if (!$countdown)
						{
							$unix = ((self::$start_jd + $col) - 2440588) * 86400;
							$month_num_days = (int) gmdate('t', $unix);
							$month_day = (int) gmdate('j', $unix);						
							$countdown = $month_num_days - $month_day + 1;
							$month = (int) gmdate('n', $unix) - 1;	
							$year = (int) gmdate('Y', $unix);
							$val = $class->ary[($month + ($year *12)) % $class->length];											
						}
						
						self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num]['class'] = $val->ary[abs((self::$start_week + $col) % $val->length)];					
						
						$countdown--;
					}
					
					unset($row_props['class']);
				}
					
				// no break here

			case self::BIRTHDAYS_TYPE:
			case self::TODAY_INDICATOR:
			case self::TARGET_INDICATOR:
			case self::TODAY_TARGET_INDICATOR:		
			case self::DAYS_TYPE:
			case self::MOONPHASE:
			case self::ISOWEEK:
			case self::MOONPHASE_ISOWEEK:
				
				unset($row_props['colspan'], $row_props['rowspan'], 
					$row_props['min_evt_rows'], $row_props['max_evt_rows']);
			
				foreach ($row_props as $key => $val)
				{
					if ($val instanceof calendar_pattern)
					{
						for ($col = 0; $col < self::$days_num; $col++)
						{
							self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num][$key] = $val->ary[abs((self::$start_week + $col) % $val->length)];
						}		
					}	
					else
					{
						for ($col = 0; $col < self::$days_num; $col++)
						{
							self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num][$key] = $val;
						}				
					}		
				}					
					
			break;
			
			case self::PERIODS_TYPE:
			case self::GREG_MONTH:
			case self::GREG_YEAR:
			case self::GREG_MONTH_YEAR:
			
				unset($row_props['rowspan']);			
			
			break;
		}
		
		//
		switch ($type)
		{
			case self::TODAY_INDICATOR:
			case self::TARGET_INDICATOR:
			case self::TODAY_TARGET_INDICATOR:

			break;
			
			case self::GREG_MONTHDAYS:
				for ($col = 0; $col < self::$days_num; $col++)
				{
					$unix = ((self::$start_jd + $col) - 2440588) * 86400;
					self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num]['text'] = gmdate('j', $unix);							
				}		
			break;
			
			case self::ISOWEEK:
			case self::MOONPHASE_ISOWEEK:
				for ($col = self::$first_monday; $col < self::$days_num; $col += 7)
				{
					$unix = ((self::$start_jd + $col) - 2440588) * 86400;
					$table_index = floor($col / self::$table_days_num);
					$table_col = $col % self::$table_days_num;
					self::$cells[$table_index][self::$row][$table_col]['class'] = 'isoweek';
					self::$cells[$table_index][self::$row][$table_col]['text'] = gmdate('W', $unix);							
				}		
				
				if ($type == self::ISOWEEK)
				{
					break;
				}
			// no break here
			
			case self::MOONPHASE:

				foreach(self::$moon_unix_ary as $key => $unix)
				{
					$local_time = $unix + ($user->data['user_timezone'] * 3600) + ($user->data['user_dst'] * 3600);				
					$jd = floor(($local_time / 86400) + 2440588);
				
					$col = $jd - self::$start_jd;
					if ($col >= 0 && $col <= self::$days_num)
					{
						$cell = &self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num];
						unset($cell['text']);					
						$cell['class'] = self::$moon_class_ary[self::$moon_phase_ary[$key]];
						$cell['title'] = $user->lang[self::$moon_text_ary[self::$moon_phase_ary[$key]]] . ' @ ' .gmdate(self::$moon_timeformat, $local_time);
					}
				}
				
				unset($cell);

			break;

			case self::GREG_MONTH:
			case self::GREG_MONTH_YEAR:
				
				$class = $row_props['class'];
				$text = $row_props['text'];
				
				for ($table = 0; $table < self::$tables_num; $table++)
				{
					$table_col = 0;
					
					do
					{

						$jd = ($table * self::$table_days_num) + $table_col + self::$start_jd;
						$unix = ($jd - 2440588) * 86400;
						$month_num_days = (int) gmdate('t', $unix);
						$month_day = (int) gmdate('j', $unix);
						$year = ($type == self::GREG_MONTH_YEAR) ? ' ' . gmdate('Y', $unix) : '';
						$month = (int) gmdate('n', $unix) - 1;
						$colspan = $month_num_days - $month_day + 1;
						$last_col = $colspan + $table_col - 1;
						
						if ($last_col >= self::$table_days_num)
						{
							$colspan = self::$table_days_num - $table_col;
						}
						
						$cell = &self::$cells[$table][self::$row][$table_col];
				
						if ($class instanceof calendar_pattern)
						{
							$cell['class'] = $class->ary[($month + ($year * 12)) % $class->length];
						}
						else
						{
							$cell['class'] = $class;
						}
						
						$cell['colspan'] = $colspan;
						
						if ($colspan > self::$text_min_span && $text instanceof calendar_pattern)
						{
							$cell['text'] = $text->ary[$month % $text->length] . $year;					
						}
						
						$table_col = $colspan + $table_col;					
					}
					while ($table_col < self::$table_days_num);
					
					unset($cell);
				}
			
			break;
			
			case self::BIRTHDAYS_TYPE:

				self::place_birthdays($row_props, $today_class);
			
			break;
			
			case self::EVENTS_TYPE :
				
				self::place_calendar_events($row_props, $today_class);
		
			break;	
		}
							
		if (isset($today_class) && self::$show_today && self::$today >= 0 && self::$today < self::$days_num && $type != self::EVENTS_TYPE && $type != self::BIRTHDAYS_TYPE)
		{
			$class = &self::$cells[floor(self::$today / self::$table_days_num)][self::$row][self::$today % self::$table_days_num]['class'];
			$class .= ((isset($class)) ? ' ' : '') . $today_class;
			unset($class);
		}	
	}
	
	/**
	**
	**/
	
	private static function place_birthdays($props, $today_class)
	{
		global $phpbb_root_path, $phpEx, $user;

		$r = 0;
		
		while($r < self::$birthday_row_count + 1)
		{
			if (sizeof(self::$birthdays[$r]))
			{
				foreach(self::$birthdays[$r] as $col => $b_ary)
				{
					$birthday['class'] = 'birthday';
					$birthday['title'] = $user->lang['BIRTHDAY_OF'] . ' ' . $b_ary['username'] . '(' . $b_ary['age'] . ')';
					$birthday['text'] = substr($b_ary['username'], 0, 2) . '..';
					$birthday['url'] = append_sid($phpbb_root_path . 'memberlist.'  . $phpEx, 'mode=viewprofile&u=' . $b_ary['user_id']);
					
					self::$cells[floor($col / self::$table_days_num)][self::$row][$col % self::$table_days_num] = $birthday;
				}
			}
	
			if (isset($today_class) && self::$show_today && self::$today >= 0 && self::$today < self::$days_num)
			{
				$class = &self::$cells[floor(self::$today / self::$table_days_num)][self::$row][self::$today % self::$table_days_num]['class'];
				$class .= ((isset($class)) ? ' ' : '') . $today_class;
				unset($class);
			}	
	
	
			self::$row++;
			$r++;
			self::$row_type_ary[self::$row] = $props['type'];
		}
		
	
		self::$row--;
	}
	
	
	
	
	/**
	**
	**/
	private static function place_calendar_events($props, $today_class)
	{
		global $auth;
	
		$e_rows_num = $props['min_evt_rows'];
		$max_rows = $props['max_evt_rows'];

		foreach (self::$cal_events as $key_cal_event => $cal_event)
		{ 		
			if (!$auth->acl_get('m_approve', $cal_event['forum_id']) && !$cal_event['topic_approved'])
			{
				continue;
			}
		
			$event_start = $cal_event['start_jd'] - self::$start_jd;
			$event_end = $cal_event['end_jd'] - self::$start_jd;

			if ($event_start < 0)
			{
				$event_start = 0;
			}
				
			if ($event_end >= self::$days_num)
			{
				$event_end = self::$days_num - 1;
			}

			$cal_evt['evt_key'] = $key_cal_event;
						
			$place_found = false;

			for ($e_row = 0; $e_row < $max_rows; $e_row++)
			{
			
				if (empty($placed_evts[$e_row]))
				{
					$place_found = true;
				}
				else
				{
					$overlap = false;
				
					foreach ($placed_evts[$e_row] as $placed_evt)
					{
					
						if (!($event_start > $placed_evt['end'] || $event_end < $placed_evt['start']))
						{
							$overlap = true;
							break;
						}
					}
				
					if (!$overlap)
					{
						$place_found = true;
					}
				}
				
				if ($place_found)
				{
					$cal_evt['class'] = 'evt';

					while (true)
					{
						$next_table = floor($event_start / self::$table_days_num) + 1;
						$next_start = $next_table * self::$table_days_num;
						
						$cal_evt['start'] = $event_start;					
						
						if ($event_end >= $next_start)
						{
							$cal_evt['end'] = $next_start - 1;
							$cal_evt['colspan'] = $next_start - $event_start;
							$placed_evts[$e_row][$event_start] = $cal_evt;
							$event_start = $next_start;	
						}
						else
						{	
							$cal_evt['end'] = $event_end;
							$cal_evt['colspan'] = $event_end - $event_start + 1;
							$placed_evts[$e_row][$event_start] = $cal_evt;				
							break;
						}
					}

					$e_rows_num = ($e_row >= $e_rows_num) ? $e_row + 1 : $e_rows_num;
					break;
				}
			}
		}
		
		self::$row--;		
		
		if (!$e_rows_num)
		{
			return;
		}
		
		$class = $props['class'];
		
		if ($class instanceof calendar_pattern)
		{
			for ($col = 0; $col < self::$days_num; $col++)
			{
				$class_ary[$col] = $class->ary[abs((self::$start_week + $col) % $class->length)];
			}
		}
		else
		{
			$class_ary = array_fill(0, self::$days_num, $props['class']);
		}
		
		if (isset($today_class) && self::$show_today && self::$today >= 0 && self::$today < self::$days_num)
		{
			$class_ary[self::$today] .= ((isset($class_ary[self::$today])) ? ' ' : '') . $today_class;
		}
		
		for ($e_row = 0; $e_row < $e_rows_num; $e_row++)
		{
			self::$row++;
			self::$row_type_ary[self::$row] = $props['type'];
				
			for ($table_index = 0; $table_index < self::$tables_num; $table_index++)
			{
				$table_start = $table_index * self::$table_days_num;
				
				$evt_col_countdown = 0;
				
				for ($table_col = 0; $table_col < self::$table_days_num; $table_col++)
				{
					$col = $table_start + $table_col;
					
					if (isset($placed_evts[$e_row][$col]))
					{
						self::$cells[$table_index][self::$row][$table_col] = $placed_evts[$e_row][$col];
						$evt_col_countdown = $placed_evts[$e_row][$col]['colspan'];
					}
					
					if ($evt_col_countdown)
					{
						$evt_col_countdown--;
					}
					else
					{
						self::$cells[$table_index][self::$row][$table_col]['class'] = $class_ary[$col];						
					}
				}					
			}	
		}
	}

	/**
	**
	**/
	public static function write_to_template()
	{
		global $template, $phpbb_root_path, $phpEx;
		
		for ($table_index = 0; $table_index < self::$tables_num; $table_index++)
		{
			self::table_to_template($table_index);
		}
		
		$hidden_calendar_fields = '<input type="hidden" value="' . self::$start_jd . '" name="start_jd" />';
		
		$template->assign_vars(array(
			'SCRIPT_MSVR_EVT_ID_ARY'		=> '[' . implode('], [', self::$mouseover_evt_id_ary) . ']',
			'S_POST_ACTION'					=> append_sid($phpbb_root_path . 'calendar.' . $phpEx) . '&start_jd=' . calendar::$start_jd . '#calendar-panel1',
			'S_HIDDEN_CALENDAR_FIELDS'		=> $hidden_calendar_fields,
			'S_DISPLAY_BIRTHDAYS'			=> self::$post_data['display_birthdays'],
			
			'U_FORUM'						=> (self::$post_data['view']) ? generate_board_url() . '/' : $phpbb_root_path
			));		

		add_form_key('postform');
	}


	/**
	**
	**/
	private static function table_to_template($table_index) 
	{
		global $template, $phpbb_root_path, $phpEx, $user, $auth, $topics_colors;
		
// topic color mod begin

		$topic_color = new topic_color();

// topic color mod end		
			
		$table_id = self::$ch_table . $table_index;
	
		$template->assign_block_vars('table', array(
			'PARAM'		=> ''
			));


		$col_start = $table_index * self::$table_days_num;
		$col_end = $col_start + self::$table_days_num;		
		
		for ($col = $col_start; $col < $col_end; $col++) 
		{	
			$template->assign_block_vars('table.col', array(
				'PARAM'		=> (isset(self::$col_class_ary[$col]) ? ' class="' . self::$col_class_ary[$col] . '"' : '')
				));				
		}
			
		foreach (self::$cells[$table_index] as $row => $cell_row)
		{
			$type = self::$row_type_ary[$row];
		
			$template->assign_block_vars('table.row', array(
				'PARAM'		=> ''
				));

			switch (self::$row_type_ary[$row])
			{
				case self::BIRTHDAYS_TYPE:	
				case self::DAYS_TYPE:
				case self::GREG_MONTHDAYS:
				case self::MOONPHASE:
				case self::MOONPHASE_ISOWEEK:
					
					for ($table_col = 0; $table_col < self::$table_days_num; $table_col++)
					{
						$cell = $cell_row[$table_col];
						
						$url = $cell['url'];
						$onclick = (isset($url)) ? ' style="cursor:pointer;" onclick="window.location.href=\'' . $url . '\';"' : '';

						$title = $cell['title'];
						$title = (isset($title)) ? ' title="' . $title . '"' : '';
						
						$template->assign_block_vars('table.row.cell', array(					
							'PARAM'		=> ((isset($cell['class'])) ? ' class="' . $cell['class'] . '"' : '') . $title . $onclick,
							'TEXT'		=> (isset($cell['text'])) ? $cell['text'] : ' '
							));		
					}
				break;
				
				case self::PERIODS_TYPE:
				case self::GREG_MONTH:
				case self::GREG_MONTH_YEAR:
				case self::GREG_YEAR:
				
					foreach($cell_row as $table_col => $cell)
					{	
						$class = (isset($cell['class'])) ? ' class="' . $cell['class'] . '"' : '';					
						$colspan = (isset($cell['colspan'])) ? ' colspan="' . $cell['colspan'] . '"' : '';

					
						$template->assign_block_vars('table.row.cell', array(					
							'PARAM'		=> $class . $colspan,
							'TEXT'		=> (isset($cell['text'])) ? $cell['text'] : ' '
							));						
					}
				
				break;
				
				case self::EVENTS_TYPE:
				
					foreach($cell_row as $table_col => $cell)
					{
						$class = (isset($cell['class'])) ? ' class="' . $cell['class'] . '"' : '';
						
						if (isset($cell['evt_key']))
						{
							$col = ($table_index * self::$table_days_num) + $table_col;
							$id = self::$ch_row . $row . self::$ch_col . $col;
							
							$cal_event = self::$cal_events[$cell['evt_key']];
							
							$topic_id = $cal_event['topic_id'];
							$forum_id = $cal_event['forum_id'];
							
							if (isset(self::$evt_topic_id_ary[$topic_id]))
							{
								$evt_id = self::$evt_topic_id_ary[$topic_id];
								self::$mouseover_evt_id_ary[$evt_id] .= ', \'' . $id . '\'';
							}
							else
							{
								$evt_id = sizeof(self::$evt_topic_id_ary);
								self::$evt_topic_id_ary[$topic_id] = $evt_id;
								self::$mouseover_evt_id_ary[$evt_id] = '\'' . $id . '\'';
							}
							
							$mouseover = ' onmouseover="mouseover_evt(' . $evt_id . ')"';
							$mouseout = ' onmouseout="mouseout_evt(' . $evt_id . ')"';

							$url = append_sid("{$phpbb_root_path}viewtopic.$phpEx", 'f=' . $forum_id . '&amp;t=' .$topic_id);
							$onclick = ' onclick="window.location.href=\'' . $url . '\'"';

							if (sizeof(self::$prefix_list[$topic_id]))
							{
								$prefixes = implode('', self::$prefix_list[$topic_id]) . ' ';
							}
							else
							{
								$prefixes = '';
							}
							
							if ($cal_event['topic_approved'] || self::$post_data['view'] == 'print')
							{
								$unapproved = '';
							}
							else
							{
								$unapproved = '<a href="';
								$unapproved .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=queue&amp;mode=approve_details&amp;t=' . $topic_id);
								$unapproved .= '">';
								$unapproved .= $user->img('icon_topic_unapproved', 'TOPIC_UNAPPROVED');
								$unapproved .= '</a>';
							}
	
							if ($cal_event['topic_reported'] && $auth->acl_get('m_report', $forum_id) && self::$post_data['view'] != 'print')
							{
								$reported = '<a href="';
								$reported .= append_sid($phpbb_root_path . 'mcp.' . $phpEx, 'i=reports&amp;mode=reports&amp;f=' . $forum_id . '&amp;t=' . $topic_id);
								$reported .= '">';
								$reported .= $user->img('icon_topic_reported', 'POST_REPORTED');
								$reported .= '</a>';
							}
							else
							{
								$reported = '';
							}
							
							$text = $prefixes . $cal_event['topic_title'];

							$colspan = $cell['colspan'];
							
							if (self::$post_data['view'] == 'print')
							{
								$color_style = '';
							}
							else
							{
								$color_style = ' style="background-color: ';
								$color_style .= $topic_color->obtain_color($cal_event['topic_bg_color'], 'topic', false, ($row % 2)) . ';"';							
							}
							
							$max_text_lenght = floor(self::$text_char_per_col * $colspan);
				
							if (strlen($text) > $max_text_lenght)
							{
								$title = ' title="' . $text . '"';							
							
								$len = ($colspan == 1) ? self::$text_limit_one_col : $max_text_lenght;
								$text = substr($text, 0, $len) . '..';
							}
							else
							{
								$title = '';
							}
		
							$colspan = ($colspan > 1) ? ' colspan="' . $cell['colspan'] . '"' : '';

							$param = ' id="' . $id . '"' . $class . $colspan . $onclick . $title . $color_style . $mouseover . $mouseout;
							$text = '<a href="' . $url . '">' . $text . '</a>' . $unapproved . $reported;
						}
						else
						{					
							$param = $class;
							$text = ' ';
						}
				
						$template->assign_block_vars('table.row.cell', array(					
							'PARAM'		=> $param,
							'TEXT'		=> $text
							));						
					}
	
				break;
			}
		}		
	}






	
	
	/**
	** Find jd_time of phases of the moon between two dates.
	** phases: 0 = new moon, 1 = first quarter, 2 = full moon, 3 = third quarter
	** output is self::$moon_phase_ary and self::$moon_unix_ary in local time
	**/
	private static function find_moonphases()  
	{
		global $user;

		$synodic_month_length = 29.53058868;	
		$deg = pi() / 180;
		$max_moon_cycles = 100; 

		$day_floor = self::$start_jd;	//	
		
		if ($day_floor >= 2299161)  
		{
			$alpha = floor(($day_floor - 1867216.25) / 36524.25);
			$day_floor = $day_floor + 1 + $alpha - floor($alpha / 4);
		}

		$day_floor += 1524;
		
		$year = floor(($day_floor - 122.1) / 365.25);
		$month = floor(($day_floor - floor(365.25 * $year)) / 30.6001);

		$month = ($month < 14) ? $month - 1 : $month - 13;
		$year = ($month > 2) ? $year - 4716 : $year - 4715;
		
		$syn_month = floor(($year + (($month - 1) * (1 / 12)) - 1900) * 12.3685) - 2;  // before :: -2
	
		for ($max_loop = $syn_month + $max_moon_cycles; $syn_month < $max_loop; $syn_month += 0.25)
		{		
			$phase = $syn_month - floor($syn_month);
				
			$jc_time = $syn_month / 1236.85;		// time in Julian centuries from 1900 January 0.5
			$jc_time2 = $jc_time * $jc_time;		// square for frequent use
			$jc_time3 = $jc_time2 * $jc_time;		// cube for frequent use

			// mean time of phase
			$phase_time = 2415020.75933
				+ $synodic_month_length * $syn_month
				+ 0.0001178 * $jc_time2
				- 0.000000155 * $jc_time3
				+ 0.00033 * sin((166.56 + 132.87 * $jc_time - 0.009173 * $jc_time2) * $deg);

			// Sun's mean anomaly
			$sun_anom = 359.2242
				+ 29.10535608 * $syn_month
				- 0.0000333 * $jc_time2
				- 0.00000347 * $jc_time3;

			// Moon's mean anomaly
			$moon_anom = 306.0253
				+ 385.81691806 * $syn_month
				+ 0.0107306 * $jc_time2
				+ 0.00001236 * $jc_time3;

			// Moon's argument of latitude
			$moon_lat = 21.2964
				+ 390.67050646 * $syn_month
				- 0.0016528 * $jc_time2
				- 0.00000239 * $jc_time3;

			if (($phase < 0.01) || (abs($phase - 0.5) < 0.01))  
			{
				// Corrections for New and Full Moon.
				$phase_time += (0.1734 - 0.000393 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.4068 * sin($moon_anom * $deg)
					+ 0.0161 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0104 * sin(2 * $moon_lat * $deg)
					- 0.0051 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0074 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0004 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0010 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0005 * sin(($sun_anom + 2 * $moon_anom) * $deg);
			}
			else if ((abs($phase - 0.25) < 0.01 || (abs($phase - 0.75) < 0.01)))  
			{
				$phase_time += (0.1721 - 0.0004 * $jc_time) * sin($sun_anom * $deg)
					+ 0.0021 * sin(2 * $sun_anom * $deg)
					- 0.6280 * sin($moon_anom * $deg)
					+ 0.0089 * sin(2 * $moon_anom * $deg)
					- 0.0004 * sin(3 * $moon_anom * $deg)
					+ 0.0079 * sin(2 * $moon_lat * $deg)
					- 0.0119 * sin(($sun_anom + $moon_anom) * $deg)
					- 0.0047 * sin(($sun_anom - $moon_anom) * $deg)
					+ 0.0003 * sin((2 * $moon_lat + $sun_anom) * $deg)
					- 0.0004 * sin((2 * $moon_lat - $sun_anom) * $deg)
					- 0.0006 * sin((2 * $moon_lat + $moon_anom) * $deg)
					+ 0.0021 * sin((2 * $moon_lat - $moon_anom) * $deg)
					+ 0.0003 * sin(($sun_anom + 2 * $moon_anom) * $deg)
					+ 0.0004 * sin(($sun_anom - 2 * $moon_anom) * $deg)
					- 0.0003 * sin((2 * $sun_anom + $moon_anom) * $deg);
					
				if ($phase < 0.5)  
				{
					// First quarter correction.
					$phase_time += 0.0028 - (0.0004 * cos($sun_anom * $deg)) + (0.0003 * cos($moon_anom * $deg));
				}
				else 
				{
					// Last quarter correction.
					$phase_time += -0.0028 + (0.0004 * cos($sun_anom * $deg)) - (0.0003 * cos($moon_anom * $deg));
				}
			}
/*			
$phase_name = array('new moon', 'first quarter moon', 'full moon', 'third quarter moon');
			
if (true) //($phase == 0.5)
{
	echo(gmdate(' D F j Y   G:i:s', (($phase_time - 2440587.5) * 86400)) . '&nbsp;&nbsp;&nbsp;&nbsp;' . $phase_name[floor(4 * $phase)] . '</br>');
}
*/			
			
			if ($phase_time >= self::$end_jd)  
			{
				return true;
			}
			
			if ($phase_time >= self::$start_jd)  
			{
				self::$moon_phase_ary[] = (int) floor(4 * $phase);
				self::$moon_unix_ary[] = ($phase_time - 2440587.5) * 86400; 
			}		
		} 
	}	
}





	






?>