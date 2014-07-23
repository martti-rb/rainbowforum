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

class acp_topic_title_prefixes
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_topic_title_prefixes');
        $this->tpl_name     = 'acp_topic_title_prefixes';
        $this->page_title   = 'ACP_TOPIC_TITLE_PREFIXES';
                            
        // 

        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			$select_ids = request_var('f', array(0 => 0));
			$prefix_group_id = request_var('prefix_group_id', 0);

			if (sizeof($select_ids))
			{		
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET prefix_group_id = ' . $prefix_group_id . '
					WHERE ' . $db->sql_in_set('forum_id', $select_ids); 
				$db->sql_query($sql);
			}
			
			$delete = request_var('prefix_delete', array(0 => 0));
			$move	= request_var('prefix_move', 0);

			if (sizeof($delete) && $delete!= array(0 => 0))
			{	
				$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_prefix_id = ' . $move . '
						WHERE ' . $db->sql_in_set('topic_prefix_id', $delete);
				$db->sql_query($sql);
				
				$db->sql_query('DELETE FROM ' . TTP_PREFIXES_TABLE . ' WHERE ' . $db->sql_in_set('prefix_id', $delete));
			}

			$delete = request_var('group_delete', array(0 => 0));
			$move	= request_var('group_move', 0);

			if (sizeof($delete) && $delete!= array(0 => 0))
			{
				$sql = 'UPDATE ' . TTP_PREFIXES_GROUPS_TABLE . '
						SET group_id = ' . $move . '
						WHERE ' . $db->sql_in_set('group_id', $delete);
				
				$db->sql_query('DELETE FROM ' . TTP_GROUPS_TABLE . ' WHERE ' . $db->sql_in_set('group_id', $delete));
			}

			$new_prefix = request_var('new_prefix', '');
			
			if ($new_prefix != '')
			{
				$sql_ary = array(
					'prefix_name'	=> $new_prefix,
					);

				$db->sql_query('INSERT INTO ' . TTP_PREFIXES_TABLE . $db->sql_build_array('INSERT', $sql_ary));
			}

			$new_group = request_var('new_group', '');
			
			if ($new_group != '')
			{
				$sql_ary = array(
					'group_name'		=> $new_group,
					);

				$db->sql_query('INSERT INTO ' . TTP_GROUPS_TABLE . $db->sql_build_array('INSERT', $sql_ary));
			}

			$group_id 	= request_var('group_select', 0);
			$add	= request_var('prefix_add', array(0 => 0));
			$remove	= request_var('prefix_remove', array(0 => 0));


			if ($group_id)
			{
				if (sizeof($add) && $add!== array(0 => 0))
				{
					foreach ($add as $prefix_id)
					{
						if (!$prefix_id)
						{
							continue;
						}
					
						$sql_ary = array(
							'prefix_id'	=> $prefix_id,
							'group_id'	=> $group_id,
							);
						$db->sql_query('INSERT INTO ' . TTP_PREFIXES_GROUPS_TABLE . $db->sql_build_array('INSERT', $sql_ary));
					}
				}
				
				if (sizeof($remove) && $remove!== array(0 => 0))
				{
					$db->sql_query('DELETE FROM ' . TTP_PREFIXES_GROUPS_TABLE . ' WHERE ' . $db->sql_in_set('prefix_id', $remove) . ' AND group_id = ' . $group_id);					
				}			
			}	
		}

		$prefixes = array();
		
		$sql = 'SELECT *
			FROM ' . TTP_PREFIXES_TABLE . ' 
			WHERE 1 = 1';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$prefixes[$row['prefix_id']] = $row['prefix_name'];
		}
		$db->sql_freeresult($result);

		if ($submit && request_var('cleanup_empty_prefix', 0))
		{
			$sql = 'UPDATE ' . TOPICS_TABLE . '
					SET topic_prefix_id = 0
					WHERE ' . $db->sql_in_set('topic_prefix_id', array_keys($prefixes), true);
			$db->sql_query($sql);
		}

		$prefix_options = '<option value="0"></option>';
		$prefix_index = 1;
		$prefix_indexes = array();
		
		foreach($prefixes as $prefix_id => &$prefix_name)
		{
			$sql = 'SELECT COUNT(topic_id) AS num
				FROM ' . TOPICS_TABLE . ' 
				WHERE topic_prefix_id = ' . $prefix_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$count = $row['num'];
			$db->sql_freeresult($result);		

			$prefix_name .= ($count) ? ' (' . $count . ')' : '';
			
			$prefix_options .= '<option value="' . $prefix_id . '">' . $prefix_name . '</option>';
			$prefix_indexes[$prefix_id] = $prefix_index; 
			$prefix_index++;
		}		

		$options = array();
		
		$sql = 'SELECT *
			FROM ' . TTP_GROUPS_TABLE . ' 
			WHERE 1 = 1';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$options[$row['group_id']] = $row['group_name'];
		}
		$db->sql_freeresult($result);		
		
		$group_options = $prefix_group_options = '<option value="0"></option>';
		
		$prefixes_in_groups = '';
		
		foreach($options as $group_id => $group_name)
		{
			$sql = 'SELECT COUNT(prefix_id) AS num
				FROM ' . TTP_PREFIXES_GROUPS_TABLE . ' 
				WHERE group_id = ' . $group_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$count = $row['num'];
			$db->sql_freeresult($result);

			$group_options .= '<option value="' . $group_id . '">' . $group_name;
			$group_options .= ($count) ? ' (' . $count . ')' : '';
			$group_options .= '</option>';

			$prefixes_in_groups .= '[';
			
			$sql = 'SELECT prefix_id
				FROM ' . TTP_PREFIXES_GROUPS_TABLE . ' 
				WHERE group_id = ' . $group_id . '
				ORDER BY prefix_id ASC';
			$result = $db->sql_query($sql);
			while($row = $db->sql_fetchrow($result))
			{
				$prefixes_in_groups .= $prefix_indexes[$row['prefix_id']] . ', ';
			}
			$db->sql_freeresult($result);

			$prefixes_in_groups = rtrim($prefixes_in_groups, ', ');
			$prefixes_in_groups .= '], ';
			

			$sql = 'SELECT COUNT(forum_id) AS num
				FROM ' . FORUMS_TABLE . ' 
				WHERE prefix_group_id = ' . $group_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$count = $row['num'];
			$db->sql_freeresult($result);		

			$prefix_group_options .= '<option value="' . $group_id . '">' . $group_name;
			$prefix_group_options .= ($count) ? ' (' . $count . ')' : '';
			$prefix_group_options .= '</option>';
		}

		$prefixes_in_groups = rtrim($prefixes_in_groups, ', ');
		
		$prefixes_groups_ary = rtrim($prefixes_groups_ary, ', ');

		$sql = 'SELECT f.forum_id, g.group_name, g.group_id
			FROM ' . FORUMS_TABLE . ' f
				LEFT JOIN ' . TTP_GROUPS_TABLE . ' g ON (g.group_id = f.prefix_group_id)
			WHERE 1 = 1
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{		
			$prefix_group_list[$row['forum_id']] = ($row['group_id']) ? ' [ ' . $row['group_name'] . ' ]' : ''; 
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
				$forum_select_options .= ' ' . $prefix_group_list[$select_option['forum_id']];
				$forum_select_options .= '</option>';
			}
		}
		
		$db->sql_freeresult($result);		
			
		$template->assign_vars(array(

			'S_FORUMS_SELECT_OPTIONS'		=> $forum_select_options, 						
			'S_PREFIX_GROUP_OPTIONS'		=> $prefix_group_options,

			'PREFIX_OPTIONS'			=> $prefix_options,
			'GROUP_OPTIONS'				=> $group_options,
			
			'PREFIXES_GROUPS_ARY'		=> $prefixes_groups_ary,
			'PREFIXES_IN_GROUPS'		=> $prefixes_in_groups,
		
			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			));
	
    }
}

?>