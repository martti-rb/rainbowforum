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

class acp_calendar
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_calendar');
        $this->tpl_name     = 'acp_calendar';
        $this->page_title   = 'ACP_CALENDAR_SETTINGS';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{
			$select_ids = request_var('f', array(0 => 0));
			$all_forums = request_var('all_forums', false) ? true : false;
			
			set_config('calendar_enable', (int) request_var('calendar_enable', 0));
			set_config('calendar_birthdays', (int) request_var('calendar_birthdays', 0));

			set_config('calendar_enable_suffix', (int) request_var('calendar_enable_suffix', 1));
			set_config('calendar_suffix_context', (int) request_var('calendar_suffix_context', 1));
			set_config('calendar_multi_suffix', (int) request_var('calendar_multi_suffix', 0));
			set_config('calendar_month_abbrev', (int) request_var('calendar_month_abbrev', 0));	
			set_config('calendar_enable_filter', (int) request_var('calendar_enable_filter', 0));			

			$calendar_min_start = (int) request_var('calendar_min_start', 730);			
			$calendar_plus_start = (int) request_var('calendar_plus_start', 1826);	
			$calendar_max_period_length = (int) request_var('calendar_max_period_length', 135);
			$calendar_max_periods = (int) request_var('calendar_max_periods', 8);
			$calendar_min_gap = (int) request_var('calendar_min_gap', 1);			
			$calendar_max_gap = (int) request_var('calendar_max_gap', 40);	
			$calendar_preset = (int) request_var('calendar_preset', 0);
			
			//

			if ($all_forums)
			{
				$select_ids = array();
			
				$sql = 'SELECT forum_id
					FROM ' . FORUMS_TABLE . '
					WHERE 1 = 1';
				$result = $db->sql_query($sql);			
			
				while ($row = $db->sql_fetchrow($result))
				{
						$select_ids[] = $row['forum_id'];					
				}	

				$db->sql_freeresult($result);				
			}

			if (sizeof($select_ids))
			{			
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET calendar_max_periods = ' . $calendar_max_periods . ',
					calendar_max_period_length = ' . $calendar_max_period_length . ',
					calendar_min_start = ' . $calendar_min_start . ',
					calendar_plus_start = ' . $calendar_plus_start . ',
					calendar_min_gap = ' . $calendar_min_gap . ',
					calendar_max_gap = ' . $calendar_max_gap . ',
					calendar_preset = ' . $calendar_preset . '
					WHERE ' . $db->sql_in_set('forum_id', $select_ids);
				$db->sql_query($sql);
			}
		}

		$sql = 'SELECT forum_id, 
				calendar_max_periods, 
				calendar_max_period_length, 
				calendar_min_start, 
				calendar_plus_start, 
				calendar_min_gap, 
				calendar_max_gap,
				calendar_preset
			FROM ' . FORUMS_TABLE . '
			WHERE 1 = 1';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$calendar_forum[$row['forum_id']] = $row;
		}
		
		$db->sql_freeresult($result);
			
		
		$forum_select = make_forum_select(false, false, false, false, true, false, true);
		
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
				
				$template->assign_block_vars('cforums', array(
					'FORUM_NAME'		=> $select_option['padding'] . $select_option['forum_name'],
					'MAX_PERIODS'		=> $calendar_forum[$select_option['forum_id']]['calendar_max_periods'],
					'MAX_PERIOD_LENGTH'	=> $calendar_forum[$select_option['forum_id']]['calendar_max_period_length'],
					'MIN_START'			=> $calendar_forum[$select_option['forum_id']]['calendar_min_start'],
					'PLUS_START'		=> $calendar_forum[$select_option['forum_id']]['calendar_plus_start'],
					'MIN_GAP'			=> $calendar_forum[$select_option['forum_id']]['calendar_min_gap'],	
					'MAX_GAP'			=> $calendar_forum[$select_option['forum_id']]['calendar_max_gap'],
					'S_PRESET'			=> $calendar_forum[$select_option['forum_id']]['calendar_preset'],					
					));
				
			}
		}		

		$template->assign_vars(array(			
			'S_CALENDAR_ENABLE'				=> ($config['calendar_enable']) ? true : false,
			'S_CALENDAR_BIRTHDAYS'			=> ($config['calendar_birthdays']) ? true : false,
			
			'S_CALENDAR_ENABLE_SUFFIX'		=> ($config['calendar_enable_suffix']) ? true : false,	
			'S_CALENDAR_SUFFIX_CONTEXT'		=> ($config['calendar_suffix_context']) ? true : false,	
			'S_CALENDAR_MULTI_SUFFIX'		=> ($config['calendar_multi_suffix']) ? true : false,	
			'S_CALENDAR_MONTH_ABBREV'		=> ($config['calendar_month_abbrev']) ? true : false,

			'S_CALENDAR_ENABLE_FILTER'	=> ($config['calendar_enable_filter']) ? true : false,
			
			'VALUE_CALENDAR_MIN_START'			=> 730,
			'VALUE_CALENDAR_PLUS_START'			=> 1826,		
			'VALUE_CALENDAR_MAX_PERIOD_LENGTH'	=> 135,	
			'VALUE_CALENDAR_MAX_PERIODS'		=> 4,	
			'VALUE_CALENDAR_MIN_GAP'			=> 1,	
			'VALUE_CALENDAR_MAX_GAP'			=> 40,
			
			'S_CALENDAR_INPUT_FORUMS_OPTIONS'	=> $forum_select_options,
		
			'S_UPDATED'							=> $submit,
			'U_ACTION'							=> $this->u_action,
			'L_TITLE'							=> $user->lang[$this->page_title],			
			));			
		
		
    }
}

?>