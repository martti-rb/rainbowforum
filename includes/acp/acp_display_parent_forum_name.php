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

class acp_display_parent_forum_name
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_display_parent_forum_name');
        $this->tpl_name     = 'acp_display_parent_forum_name';
        $this->page_title   = 'ACP_DISPLAY_PARENT_FORUM_NAME';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{
			$select_ids = request_var('f', array(0 => 0));
			$all_forums = request_var('all_forums', false) ? true : false;
			$enable_pfn = request_var('enable_pfn', 0);

			set_config('display_forum_parent_name_enable', (int) $enable_pfn);
			
			$sql = 'SELECT forum_id, parent_id
				FROM ' . FORUMS_TABLE . '
				WHERE 1 = 1';
			$result = $db->sql_query($sql);

			if ($all_forums)
			{
				$select_ids = array();
			
				while ($row = $db->sql_fetchrow($result))
				{
					if ($row['parent_id'])
					{
						$select_ids[] = $row['forum_id'];					
					}
					else
					{			
						$ignore_ids[] = $row['forum_id'];
					}
				}		
			}
			else
			{
				while ($row = $db->sql_fetchrow($result))
				{
					if (!($row['parent_id']))
					{			
						$ignore_ids[] = $row['forum_id'];
					}
				}
			}
			
			$db->sql_freeresult($result);

			$sql = 'UPDATE ' . FORUMS_TABLE . '
				SET display_parent_forum = 0
				WHERE 1 = 1';
			$db->sql_query($sql);

			if (sizeof($select_ids))
			{			
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET display_parent_forum = 1
					WHERE ' . $db->sql_in_set('forum_id', $select_ids);
				$db->sql_query($sql);
			}
		}
		else
		{
			$select_ids = array();
			$ignore_ids = array();
		
			$sql = 'SELECT forum_id, parent_id
				FROM ' . FORUMS_TABLE . '
				WHERE display_parent_forum = 1
					OR parent_id = 0';
			$result = $db->sql_query($sql);

			while ($row = $db->sql_fetchrow($result))
			{
				if ($row['parent_id'])
				{
					$select_ids[] = $row['forum_id'];
				}
				else
				{			
					$ignore_ids[] = $row['forum_id'];
				}
			}
			
			$db->sql_freeresult($result);
		}

		$template->assign_vars(array(
			'S_ENABLE_PFN'					=> ($config['display_forum_parent_name_enable']) ? true : false,
			'S_UPDATED'						=> $submit,
			'S_FORUMS_SELECT_OPTIONS'		=> make_forum_select($select_ids, $ignore_ids, false, false, false, false, false),
			'U_ACTION'						=> $this->u_action,
			'L_TITLE'						=> $user->lang[$this->page_title],			
			));				
    }
}

?>