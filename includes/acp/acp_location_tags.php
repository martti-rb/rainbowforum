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

class acp_location_tags
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_location_tags');
        $this->tpl_name     = 'acp_location_tags';
        $this->page_title   = 'ACP_LOCATION_TAGS';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{
			$select_ids = request_var('f', array(0 => 0));
			$all_forums = request_var('all_forums', false) ? true : false;
			$locations_per_topic	= request_var('locations_per_topic', 0);

			set_config('location_tags_enable', request_var('location_tags_enable', 0));

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
					SET locations_per_topic = ' . $locations_per_topic . '
					WHERE ' . $db->sql_in_set('forum_id', $select_ids);
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
				$forum_select_options .= ($location_tags_per_topic[$select_option['forum_id']]) ? ' (' . $location_tags_per_topic[$select_option['forum_id']] . ')' : '';
				$forum_select_options .= '</option>';
			}
		}

		$template->assign_vars(array(
			'S_LOCATION_TAGS_ENABLE'		=> ($config['location_tags_enable']) ? true : false,
			'S_UPDATED'						=> $submit,
			'S_FORUM_SELECT_OPTIONS'		=> $forum_select_options,
			'U_ACTION'						=> $this->u_action,
			'L_TITLE'						=> $user->lang[$this->page_title],			
			));				
    }
}

?>