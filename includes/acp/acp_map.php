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

class acp_map
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_map');
        $this->tpl_name     = 'acp_map';
        $this->page_title   = 'ACP_MAP';
                         
        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			set_config('map_enable', request_var('map_enable', 0));
			set_config('google_api_key', request_var('google_api_key', ''));


			$select_ids = request_var('f', array(0 => 0));
			$all_forums = request_var('all_forums', false) ? '1 = 1' : '1 = 0';

		/*
			$sql = 'UPDATE ' . FORUMS_TABLE . '
				SET map_enable = ' . $select_ids_auto_archive_enable . '
				WHERE ' . $db->sql_in_set('forum_id', $select_ids) . ' 
					OR ' . $all_forums;
			$db->sql_query($sql);
		*/
		}
		else
		{
			$select_ids = false;
		}
/*		
		$sql = 'SELECT forum_id, auto_archive_enable, auto_archive_after_days
			FROM ' . FORUMS_TABLE . '
			ORDER BY left_id ASC';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$auto_archive_list[$row['forum_id']] = ' [ ';		
			$auto_archive_list[$row['forum_id']] .= ($row['auto_archive_enable']) ? $user->lang['ENABLE'] : $user->lang['DISABLE'];
			$auto_archive_list[$row['forum_id']] .= ' - ' . $row['auto_archive_after_days'] . $user->lang['DAYS'] . ' ]';
		}
		
		$db->sql_freeresult($result);		
*/
		
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
			'S_MAP_ENABLE'				=> ($config['map_enable']) ? true : false,
			'GOOGLE_API_KEY'			=> $config['google_api_key'],
			'FORUM_SELECT_OPTIONS'		=> $forum_select_options,

			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			));
    }

}

?>