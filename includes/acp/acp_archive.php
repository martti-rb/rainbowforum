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

class acp_archive
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_archive');
        $this->tpl_name     = 'acp_archive';
        $this->page_title   = 'ACP_ARCHIVE';
                         
        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			set_config('archive_id', request_var('archive_id', 49));
			set_config('auto_archive_enable', request_var('auto_archive_enable', 0));
			set_config('auto_archive_edit_time', request_var('auto_archive_edit_time', 86400));
			set_config('auto_archive_gc', request_var('auto_archive_cron_time', 86400));

			$select_ids = request_var('f', array(0 => 0));
			$all_forums = request_var('all_forums', false) ? '1 = 1' : '1 = 0';
			$select_ids_auto_archive_enable = request_var('auto_archive_forum_enable', false) ? 1 : 0;
			$select_ids_auto_archive_after_days = request_var('auto_archive_after_days', 1);

			if ($select_ids[0] == 0)
			{
				unset($select_ids);
			}
			
			if (sizeof($select_ids))
			{
				$sql = 'UPDATE ' . FORUMS_TABLE . '
					SET auto_archive_enable = ' . $select_ids_auto_archive_enable . ',
						auto_archive_after_days = ' . $select_ids_auto_archive_after_days . '
					WHERE ' . $db->sql_in_set('forum_id', $select_ids) . ' 
						OR ' . $all_forums;
				$db->sql_query($sql);
			}
		}
		else
		{
			$select_ids = false;
		}
		
		$sql = 'SELECT forum_id, auto_archive_enable, auto_archive_after_days
			FROM ' . FORUMS_TABLE . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$auto_archive_list[$row['forum_id']] = ($row['auto_archive_enable']) ? '[ ' . $row['auto_archive_after_days'] . ' ' . $user->lang['DAYS'] . ' ]' : '';		
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
				$forum_select_options .= ' ' . $auto_archive_list[$select_option['forum_id']];
				$forum_select_options .= '</option>';
			}
		}

		
		$template->assign_vars(array(
			'ARCHIVE_ID'				=> $config['archive_id'],
			'S_AUTO_ARCHIVE_ENABLE'		=> ($config['auto_archive_enable']) ? true : false,
			'FORUM_SELECT_OPTIONS'		=> $forum_select_options,
			'S_ARCHIVE_FORUM_ENABLE'	=> false,
			'ARCHIVE_AFTER_DAYS'		=> 1,
			'EDIT_TIME'					=> $config['auto_archive_edit_time'],
			'CRON_TIME'					=> $config['auto_archive_gc'],
			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			));
    }

}

?>