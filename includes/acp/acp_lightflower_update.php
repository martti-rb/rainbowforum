<?php
/**
*
* @package acp
* @version $Id$
* @copyright (c) 2005 phpBB Group / 2011 Zmarty
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

/**
* @package acp
*/

class acp_lightflower_update
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_lightflower_update');
        $this->tpl_name     = 'acp_lightflower_update';
        $this->page_title   = 'ACP_LIGHTFLOWER_UPDATE';
                            
        // 
		
		
        $submit	= isset($_POST['submit']) ? true : false;

		$forum_sel = request_var('fl', 0);
		$continent_sel = request_var('continent_select', 0);
		$erase_location = request_var('erase_location', 0);
		$auto_location = request_var('auto_location', 0);

		$forum_bg_colors = array();

		$sql = 'SELECT forum_id, forum_bg_color  
					FROM ' . FORUMS_TABLE;
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$forum_bg_colors[$row['forum_id']] = $row['forum_bg_color'];	
		}
		$db->sql_freeresult($result);		
		

		if ($submit)
		{
			if (request_var('user_birthdays', 0))
			{
				$updated = true;
				
				$bday = array();
		
				$sql = 'SELECT user_id, user_birthday 
							FROM ' . USERS_TABLE . '
							WHERE user_birthday';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$bday[$row['user_id']] = $row['user_birthday'];	
				}
				$db->sql_freeresult($result);

				if (isset($bday))
				{
					foreach ($bday as $user_id => $bd)
					{
						list($bday_day, $bday_month, $bday_year) = explode('-', $bd);
					
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_bday_day = ' . $bday_day . ',
								user_bday_month = ' . $bday_month . ',
								user_bday_year = ' . $bday_year . '
							WHERE user_id = ' . $user_id;
						$db->sql_query($sql);	
					}
				}

				unset($bday);
			}			

			
			if (request_var('prefixes', 0))
			{
				$updated = true;
		
				$sql = 'SELECT forum_id, group_id 
							FROM ' . TTP_FORUMS_GROUPS_TABLE . '
							WHERE 1 = 1';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$ttp[] = $row;	
				}
				$db->sql_freeresult($result);

				if (isset($ttp))
				{
					foreach ($ttp as $ttpp)
					{
						$sql = 'UPDATE ' . FORUMS_TABLE . '
							SET prefix_group_id = ' . $ttpp['group_id'] . '
							WHERE forum_id = ' . $ttpp['forum_id'];
						$db->sql_query($sql);	
					}
				}

				unset($ttp);

				$sql = 'SELECT topic_id, prefix_id 
							FROM ' . TTP_TOPICS_PREFIXES_TABLE . '
							WHERE 1 = 1';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$ttp[] = $row;	
				}
				$db->sql_freeresult($result);

				if (isset($ttp))
				{
					foreach ($ttp as $ttpp)
					{
						$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET topic_prefix_id = ' . $ttpp['prefix_id'] . '
							WHERE topic_id = ' . $ttpp['topic_id'];
						$db->sql_query($sql);	
					}
				}

				unset($ttp);
			}
			
			if (request_var('randomize_topic_colors', 0))
			{
				$updated = true;
				
				$dark = request_var('topic_dark_bg', 0);
				$random_dark = request_var('topic_dark_bg_random', 0);
				
				$topic_list = array();
		
				$sql = 'SELECT topic_id 
							FROM ' . TOPICS_TABLE;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$topic_list[] = $row['topic_id'];	
				}
				$db->sql_freeresult($result);

				if (isset($topic_list))
				{
					foreach ($topic_list as $topic_id)
					{
						$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET topic_bg_color = ' . mt_rand($config['topic_color_preset_min'], $config['topic_color_preset_max']) . ',
							topic_bg_color_dark = ' . (($random_dark) ? mt_rand(0, 1) : (($dark) ? 1 : 0)) . '				
							WHERE topic_id = ' . $topic_id;
						$db->sql_query($sql);	
					}
				}

				unset($topic_list);
			}			
			
			if (request_var('randomize_forum_colors', 0))
			{
				$updated = true;
				
				$forum_list = array();
		
				$sql = 'SELECT forum_id 
							FROM ' . FORUMS_TABLE;
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$forum_list[] = $row['forum_id'];	
				}
				$db->sql_freeresult($result);

				if (isset($forum_list))
				{
					foreach ($forum_list as $forum_id)
					{
						$sql = 'UPDATE ' . FORUMS_TABLE . '
							SET forum_bg_color = ' . mt_rand($config['forum_color_preset_min'], $config['forum_color_preset_max']) . '				
							WHERE forum_id = ' . $forum_id;
						$db->sql_query($sql);	
					}
				}

				unset($topic_list);
			}			

			$forum_list_to_color = request_var('flc', array(0 => 0));
			
			if (sizeof($forum_list_to_color))
			{
				$updated = true;
				
				$forum_bg_color = request_var('forum_color_value', 50);
				
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET forum_bg_color =  ' . $forum_bg_color . '
					WHERE ' . $db->sql_in_set('forum_id', $forum_list_to_color);
				$db->sql_query($sql);

				foreach($forum_list_to_color as $forum_id)
				{
					$forum_bg_colors[$forum_id] = $forum_bg_color;
				}
			}

			if (request_var('topic_colors_match', 0))
			{
				$updated = true;

				if (sizeof($forum_bg_colors))
				{
					foreach ($forum_bg_colors as $forum_id => $forum_bg_color)
					{
						$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET topic_bg_color = ' . $forum_bg_color . ',
							topic_bg_color_dark = 0				
							WHERE forum_id = ' . $forum_id;
						$db->sql_query($sql);	
					}
				}
			}


			if (request_var('update_calendar', 0))
			{
				$updated = true;
		
				$min = $max = $num = array();

				$sql = 'SELECT COUNT(start_jd) AS num, 
							MIN(start_jd) AS min_jd,
							MAX(end_jd) AS max_jd,
							topic_id
							FROM ' . CALENDAR_PERIODS_TABLE . '
							GROUP BY topic_id';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$num[$row['topic_id']] = $row['num'];
					$greg = cal_from_jd($row['min_jd'],CAL_GREGORIAN);
					$min[$row['topic_id']] = $greg['year'];
					$greg = cal_from_jd($row['max_jd'],CAL_GREGORIAN);
					$max[$row['topic_id']] = $greg['year'];			
				}
				$db->sql_freeresult($result);
				
				foreach($num as $topic_id => $num)
				{
					$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET calendar_period_num = ' . $num . ',
								calendar_min_year = ' . $min[$topic_id] . ',
								calendar_max_year = ' . $max[$topic_id] . '
							WHERE topic_id = ' . $topic_id;
					$db->sql_query($sql);
				}
			}			
			
			if (request_var('update_locations', 0))
			{
				$updated = true;
		
				$num = array();

				$sql = 'SELECT COUNT(location_a) AS num, topic_id
							FROM ' . TOPICS_LOCATIONS_TABLE . '
							GROUP BY topic_id';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$num[$row['topic_id']] = $row['num'];		
				}
				$db->sql_freeresult($result);
				
				$sql = 'SELECT location_a, location_b, location_c, topic_id
							FROM ' . TOPICS_LOCATIONS_TABLE . '
							ORDER BY id ASC';
				$result = $db->sql_query($sql);

				while ($row = $db->sql_fetchrow($result))
				{
					$loc_a[$row['topic_id']][] = $row['location_a'];
					$loc_b[$row['topic_id']][] = $row['location_b'];
					$loc_c[$row['topic_id']][] = $row['location_c'];					
				}
				$db->sql_freeresult($result);				

				foreach($num as $topic_id => $num)
				{
					$loc_a1 = ($loc_a[$topic_id][0]) ? $loc_a[$topic_id][0] : 0;
					$loc_a2 = ($loc_a[$topic_id][1]) ? $loc_a[$topic_id][1] : 0;
					$loc_b1 = ($loc_b[$topic_id][0]) ? $loc_b[$topic_id][0] : 0;					
					$loc_c1 = ($loc_c[$topic_id][0]) ? $loc_c[$topic_id][0] : 0;					
					
				
					$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET location_num = ' . $num . ',
								location_a1 = ' . $loc_a1 . ',
								location_a2 = ' . $loc_a2 . ',
								location_b1 = ' . $loc_b1 . ',
								location_c1 = ' . $loc_c1 . '
							WHERE topic_id = ' . $topic_id;
					$db->sql_query($sql);
				}
			}

			$sql = 'SELECT forum_id, locations_per_topic
				FROM ' . FORUMS_TABLE . '
				WHERE 1 = 1';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				$location_tags_per_topic[$row['forum_id']] = $row['locations_per_topic'];
			}
			
			$db->sql_freeresult($result);

			if ($forum_sel)
			{
				$forum_list = array();
				$topic_list = array();
				$topic_titles = array();
				$topics_forum = array();
			
				$sql = ' SELECT f2.forum_id
							FROM ' . FORUMS_TABLE . ' f2 
							LEFT JOIN ' . FORUMS_TABLE . ' f1 ON (f1.forum_id = f2.forum_id OR (f1.left_id < f2.left_id AND f1.right_id > f2.right_id)) 
						WHERE f1.forum_id = ' . $forum_sel;
				$result = $db->sql_query($sql);
			
				while ($row = $db->sql_fetchrow($result))
				{
					$forum_list[$row['forum_id']] = $row['forum_id'];
				}
				$db->sql_freeresult($result);

				if ($erase_location)
				{
					$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET location_num = 0,
								location_a1 = 0,
								location_a2 = 0,
								location_b1 = 0,
								location_c1 = 0
							WHERE ' . $db->sql_in_set('forum_id', $forum_list) . '
								OR '  . $db->sql_in_set('archive_forum_id', $forum_list);		
					$db->sql_query($sql);
				}

				$sql = ' SELECT t.topic_id, t.forum_id, t.topic_title
							FROM ' . TOPICS_TABLE . ' t
							LEFT JOIN ' . FORUMS_TABLE . ' f2 ON (f2.forum_id = t.forum_id OR f2.forum_id = t.archive_forum_id)
							LEFT JOIN ' . FORUMS_TABLE . ' f1 ON (f1.forum_id = f2.forum_id OR (f1.left_id < f2.left_id AND f1.right_id > f2.right_id)) 
						WHERE f1.forum_id = ' . $forum_sel . '
							AND t.location_a2 = 0
							AND t.location_b1 = 0
							AND t.location_c1 = 0';
				$result = $db->sql_query($sql);
			
				while ($row = $db->sql_fetchrow($result))
				{
					$topic_list[$row['topic_id']] = $row['topic_id'];
					$topics_forum[$row['forum_id']][] = $row['topic_id'];
					$topic_titles[$row['topic_id']] = $row['topic_title'];
				}
				$db->sql_freeresult($result);
				
				if ($erase_location)
				{
					$sql = 'DELETE FROM ' . TOPICS_LOCATIONS_TABLE . '
						WHERE ' . $db->sql_in_set('topic_id', $topic_list);
					$db->sql_query($sql);			
				}

				if ($continent_sel)
				{
					$sql = 'UPDATE ' . TOPICS_TABLE . '
							SET location_num = 1,
								location_a1 = ' . $continent_sel . '
							WHERE (' . $db->sql_in_set('forum_id', $forum_list) . '
								OR ' . $db->sql_in_set('archive_forum_id', $forum_list) . ')
								AND location_a2 = 0 
								AND location_b1 = 0 
								AND location_c1 = 0';		
					$db->sql_query($sql);
					
					$sql = 'DELETE FROM ' . TOPICS_LOCATIONS_TABLE . '
							WHERE ' . $db->sql_in_set('topic_id', $topic_list);
					$db->sql_query($sql);	
	
					foreach($topic_list as $topic_id)
					{
						$sql_ary = array(
							'topic_id'		=>	$topic_id,
							'location_a'	=>	$continent_sel,
							'location_b'	=>	0,
							'location_c'	=>	0,
						);

						$db->sql_query('INSERT INTO ' . TOPICS_LOCATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));
					}
				}				
				
				if ($auto_location)
				{
					$auto_topic = array();
				
					$sql = 'SELECT l.id, l.name, l.parent_id, l.level, t.topic_id, t.topic_title, t.location_a1
							FROM ' . TOPICS_TABLE . ' t
							LEFT JOIN ' . LOCATIONS_TABLE . ' l 
								ON ((LOWER(t.topic_title) LIKE CONCAT(\'%\', LOWER(l.name), \'%\'))
									OR (l.search_1 <> \'\' AND (LOWER(t.topic_title) LIKE CONCAT(\'%\', LOWER(l.search_1), \'%\')))
									OR (l.search_2 <> \'\' AND (LOWER(t.topic_title) LIKE CONCAT(\'%\', LOWER(l.search_2), \'%\')))
									OR (l.search_3 <> \'\' AND (LOWER(t.topic_title) LIKE CONCAT(\'%\', LOWER(l.search_3), \'%\'))))
							WHERE l.name <> \'\'
								AND ' . $db->sql_in_set('topic_id', $topic_list);
					$result = $db->sql_query($sql);
			
					while ($row = $db->sql_fetchrow($result))
					{					
						$auto_topic[$row['topic_id']][] = $row;
					}
					$db->sql_freeresult($result);				

					if (sizeof($auto_topic))
					{
						$auto_topic_ary = '';
					
						foreach($auto_topic	as $topic_id => $ary)
						{
							$multi = (sizeof($ary) > 1) ? 'x' : '';
							$checked = (sizeof($ary) > 1) ? false : true;
							
							foreach ($ary as $rrow)
							{
								$parent_mismatch = ($rrow['location_a1'] == $rrow['parent_id']) ? '' : 'x';
								
								$template->assign_block_vars('atopics', array(
											'TOPIC_TITLE'	=> $rrow['topic_title'],
											'TOPIC_ID'		=> $rrow['topic_id'],
											'LOCATION'		=> $rrow['name'],
											'MULTI'			=> $multi,
											'PARENT_MISMATCH'	=> $parent_mismatch,
											'CHECKED'		=> $checked,
											'LOC_ID'		=> $rrow['id'],
											'TOPIC_ID'		=> $rrow['topic_id'],
											));									
								
							}
							
							$auto_topic_ary .= $topic_id . '_';
						}
						
						$auto_topic_ary = rtrim($auto_topic_ary, '_');
					}
				}
			}
			
			$auto_ary = request_var('auto_topic_ary', '');

			$locations = array();
			
			$sql= 'SELECT l.id, l.name, l.parent_id, l.level
					FROM ' . LOCATIONS_TABLE . ' l';
			$result = $db->sql_query($sql);
			while ($row = $db->sql_fetchrow($result))
			{					
				$locations[$row['id']] = $row;
			}
			$db->sql_freeresult($result);

			if ($auto_ary)
			{
				$auto_ary = explode('_', $auto_ary);

				if (sizeof($auto_ary))
				{
					foreach ($auto_ary as $topic_id)
					{
						$loc_ary = request_var('t' . $topic_id, array(0 => 0));

						if (sizeof($loc_ary))
						{
						
							$sql = 'SELECT f.locations_per_topic
									FROM ' . FORUMS_TABLE . ' f
									LEFT JOIN ' . TOPICS_TABLE . ' t 
										ON ((t.forum_id = f.forum_id AND t.forum_id <> ' . $config['archive_id'] . ') 
											OR (t.forum_id = ' . $config['archive_id'] . ' AND t.archive_forum_id = f.forum_id))
									WHERE t.topic_id = ' . $topic_id;
									$result = $db->sql_query($sql);
									$row = $db->sql_fetchrow($result);				
									$max_locations = $row['locations_per_topic'];
									$db->sql_freeresult($result);
						
							foreach($loc_ary as $key => $loc_id)
							{
								if ($key < $max_locations)
								{
									$abort = false;
								
									switch ($locations[$loc_id]['level'])
									{
										case 0: 
											$abort = true;
											break;
										case 1:
											$loc_a = $loc_id;
											$loc_b = 0;
											$loc_c = 0;
											break;
										case 2:
											$loc_a = $locations[$loc_id]['parent_id'];
											$loc_b = $loc_id;
											$loc_c = 0;
											break;
										case 3: 
											$loc_a = $locations[$locations[$loc_id]['parent_id']]['parent_id'];
											$loc_b = $locations[$loc_id]['parent_id'];
											$loc_c = $loc_id;
											break;
										default:
											$abort = true;
											break;	
									}
									
									$sql = 'SELECT t.location_a1, t.location_a2, t.location_b1, t.location_c1
										FROM ' . TOPICS_TABLE . ' t 
										WHERE t.topic_id = ' . $topic_id;
										$result = $db->sql_query($sql);
										$row = $db->sql_fetchrow($result);				
										$loc_a1 = $row['location_a1'];
										$loc_a2 = $row['location_a2'];
										$loc_b1 = $row['location_b1'];
										$loc_c1 = $row['location_c1'];
										$db->sql_freeresult($result);
									
									if ($loc_a1 && $loc_a2)
									{
										$abort = true;
									}
									else
									{
										if ($loc_a1)
										{
											if ($loc_a1 == $loc_a)
											{
												$sql = 'UPDATE ' . TOPICS_TABLE . '
													SET location_b1 = ' . $loc_b . ',
														location_c1 = ' . $loc_c . ',
														location_a2 = 0
													WHERE topic_id = ' . $topic_id;	
												$db->sql_query($sql);

												$sql = 'DELETE FROM ' . TOPICS_LOCATIONS_TABLE . '
													WHERE topic_id = ' . $topic_id;
												$db->sql_query($sql);	
											}
											else
											{
												$sql = 'UPDATE ' . TOPICS_TABLE . '
													SET location_num = 2,
														location_a2 = ' . $loc_a . '
													WHERE topic_id = ' . $topic_id;	
												$db->sql_query($sql);
											}
										}
										else
										{
											$sql = 'UPDATE ' . TOPICS_TABLE . '
												SET location_num = 1,
													location_a1 = ' . $loc_a . ',
													location_b1 = ' . $loc_b . ',
													location_c1 = ' . $loc_c . '
												WHERE topic_id = ' . $topic_id;		
											$db->sql_query($sql);
										}
										
										$sql_ary = array(
											'topic_id'		=>	$topic_id,
											'location_a'	=>	$loc_a,
											'location_b'	=>	$loc_b,
											'location_c'	=>	$loc_c,
											);

										$db->sql_query('INSERT INTO ' . TOPICS_LOCATIONS_TABLE . ' ' . $db->sql_build_array('INSERT', $sql_ary));	
									}
								}
								else
								{
									$abort = true;
								}
								
								if ($abort)
								{
									$sql = 'SELECT t.topic_title
										FROM ' . TOPICS_TABLE . ' t 
										WHERE t.topic_id = ' . $topic_id;
									$result = $db->sql_query($sql);
									$row = $db->sql_fetchrow($result);				
									$topic_title = $row['topic_title'];
									$db->sql_freeresult($result);

									$template->assign_block_vars('aborted', array(
										'TOPIC_TITLE'	=> $topic_title,
										'TOPIC_ID'		=> $topic_id,
										));							
								}
							}
						}
					}
				}
			}
			
			if ($forum_sel)
			{
				$topic_list = array();
				$topic_titles = array();
				
				$sql = ' SELECT t.topic_id, t.topic_title
							FROM ' . TOPICS_TABLE . ' t
							LEFT JOIN ' . FORUMS_TABLE . ' f2 
								ON (f2.forum_id = t.forum_id OR f2.forum_id = t.archive_forum_id)
							LEFT JOIN ' . FORUMS_TABLE . ' f1 
								ON (f1.forum_id = f2.forum_id OR (f1.left_id < f2.left_id AND f1.right_id > f2.right_id)) 
						WHERE f1.forum_id = ' . $forum_sel;
				$result = $db->sql_query($sql);
			
				while ($row = $db->sql_fetchrow($result))
				{
					$topic_list[$row['topic_id']] = $row['topic_id'];
					$topic_titles[$row['topic_id']] = $row['topic_title'];
				}
				$db->sql_freeresult($result);

				$topic_locations = array();
			
				$sql = 'SELECT tl.topic_id, tl.location_a, tl.location_b, tl.location_c
							FROM ' . TOPICS_LOCATIONS_TABLE . ' tl
						WHERE ' . $db->sql_in_set('tl.topic_id', $topic_list);
				$result = $db->sql_query($sql);
			
				while ($row = $db->sql_fetchrow($result))
				{
					$location = ($row['location_c']) ? $row['location_c'] : (($row['location_b']) ? $row['location_b'] : $row['location_a']);	
					$topic_locations[$row['topic_id']][] = $location;
				}
				$db->sql_freeresult($result);

				foreach ($topic_list as $topic_id)
				{
					$template->assign_block_vars('showtopics', array(
						'TOPIC_TITLE'	=> $topic_titles[$topic_id],
						'TOPIC_ID'		=> $topic_id,
						'LOCATION_1'	=> $locations[$topic_locations[$topic_id][0]]['name'],
						'LOCATION_2'	=> $locations[$topic_locations[$topic_id][1]]['name'],						
						));
				}  
			}
			
			$archive_to = request_var('fa', 0);
			
			if ($archive_to)
			{
				$archive_from = request_var('fai', array(0 => 0));
				
				if (sizeof($archive_from) && $archive_from[0])
				{
					$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET archive_forum_id = ' . $archive_to . '
						WHERE  ' . $db->sql_in_set('archive_forum_id', $archive_from);	
					$db->sql_query($sql);					
				}
			}
		}
		
		$sql = 'SELECT DISTINCT COUNT(topic_id) AS topic_num, archive_forum_id
				FROM ' . TOPICS_TABLE . '
				WHERE forum_id = ' . $config['archive_id'] . '
				GROUP BY archive_forum_id';
		$result = $db->sql_query($sql);
	
		while ($row = $db->sql_fetchrow($result))
		{
			$archived_forum_topic_count[$row['archive_forum_id']] = $row['topic_num'];
		}
		$db->sql_freeresult($result);		

		$forum_select = make_forum_select($forum_sel, false, false, false, true, false, true);
		
		$forum_select_options = '<option value="0"';
		$forum_select_options .= ($forum_sel) ? '' : ' selected="selected"';
		$forum_select_options .= '></option>';
		
		$forum_color_select_options = $archive_forum_select_options = $forum_select_options;
		
		if (sizeof($forum_select))
		{
			foreach ($forum_select as $select_option)
			{
				$forum_color_select_options .= '<option value="' . $select_option['forum_id'] . '"';
				$forum_color_select_options .= '>' . $select_option['padding'] . $select_option['forum_name'];
				$forum_color_select_options .= ' (' . $forum_bg_colors[$select_option['forum_id']] . ')';
				$forum_color_select_options .= '</option>';			
			
				$forum_select_options .= '<option value="' . $select_option['forum_id'] . '"';
				$forum_select_options .= ($select_option['disabled']) ? ' disabled="disabled" class="disabled-option"' : '';
				$forum_select_options .= ($select_option['selected']) ? ' selected="selected"' : '';
				$forum_select_options .= '>' . $select_option['padding'] . $select_option['forum_name'];
				$forum_select_options .= ($forum_list[$select_option['forum_id']]) ? ' x' : '';
				$forum_select_options .= (sizeof($topics_forum[$select_option['forum_id']])) ? ' (' . sizeof($topics_forum[$select_option['forum_id']]) . ')' : '';
				$forum_select_options .= '</option>';
				
				$archive_forum_select_options .= '<option value="' . $select_option['forum_id'] . '"';
				$archive_forum_select_options .= ($archived_forum_topic_count[$select_option['forum_id']]) ? '' : ' disabled="disabled" class="disabled-option"';
				$archive_forum_select_options .= ($select_option['selected']) ? ' selected="selected"' : '';
				$archive_forum_select_options .= '>' . $select_option['padding'] . $select_option['forum_name'];
				$archive_forum_select_options .= ($archived_forum_topic_count[$select_option['forum_id']]) ? ' (' . $archived_forum_topic_count[$select_option['forum_id']] . ')' : '';
				$archive_forum_select_options .= '</option>';				
				
			}
		}
		
		$sql = 'SELECT name, id
			FROM ' . LOCATIONS_TABLE . ' l
			WHERE l.level = 1
			ORDER BY l.name ASC';
		$result = $db->sql_query($sql);
		
		$continent_select_options = '<option value="0"';
		$continent_select_options .= ($continent_sel) ? '' : ' selected="selected"';
		$continent_select_options .= '></option>';		

		while ($row = $db->sql_fetchrow($result))
		{
			$continent_select_options .= '<option value="' . $row['id'] . '"';
			$continent_select_options .= ($continent_sel == $row['id']) ? ' selected="selected"' : '';
			$continent_select_options .= '>' . $row['name'] . '</option>';	
		}		
		$db->sql_freeresult($result);		
	


	
		$template->assign_vars(array(			
			'S_FORUM_SELECT_OPTIONS'			=> $forum_select_options,
			'S_CONTINENT_SELECT_OPTIONS'		=> $continent_select_options,
			'S_ARCHIVE_FORUM_SELECT_OPTIONS'	=> $archive_forum_select_options,
			'S_FORUM_COLOR_SELECT_OPTIONS'		=> $forum_color_select_options,
	
			'AUTO_TOPIC_ARY'					=> $auto_topic_ary,
			'S_UPDATED'							=> $updated,
			'U_ACTION'							=> $this->u_action,
			'L_TITLE'							=> $user->lang[$this->page_title],			
			));			
		
		
    }
}

?>