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

class acp_link
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_link');
        $this->tpl_name     = 'acp_link';
        $this->page_title   = 'ACP_LINK';
                         
        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			set_config('link_forum_id', request_var('link_forum_id', 0));
			set_config('links_per_post', request_var('links_per_post', 0));
			
			$delete = request_var('lnksrcdel', array(0 => 0));
			$move	= request_var('lnksrcmov', 0);

			if (sizeof($delete) && $delete!= array(0 => 0))
			{
				$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_link_source_id = ' . $move . '
						WHERE ' . $db->sql_in_set('topic_source_type_id', $delete);
				
				$db->sql_query('DELETE FROM ' . LINK_TYPES_TABLE . ' WHERE ' . $db->sql_in_set('id', $delete));
			}			
			
			$delete = request_var('lnktpdel', array(0 => 0));
			$move	= request_var('lnktpmov', 0);

			if (sizeof($delete) && $delete!= array(0 => 0))
			{
				$sql = 'UPDATE ' . TOPICS_TABLE . '
						SET topic_link_type_id = ' . $move . '
						WHERE ' . $db->sql_in_set('topic_link_type_id', $delete);
				
				$db->sql_query('DELETE FROM ' . LINK_TYPES_TABLE . ' WHERE ' . $db->sql_in_set('id', $delete));
			}

			$new_link_source = request_var('new_link_source', '');
			
			if ($new_link_source != '')
			{
				$sql_ary = array(
					'name'		=> $new_link_source,
					'is_source'	=> 1,
					);

				$db->sql_query('INSERT INTO ' . LINK_TYPES_TABLE . $db->sql_build_array('INSERT', $sql_ary));
			}

			$new_link_type = request_var('new_link_type', '');
			
			if ($new_link_type != '')
			{
				$sql_ary = array(
					'name'		=> $new_link_type,
					'is_source'	=> 0,
					);

				$db->sql_query('INSERT INTO ' . LINK_TYPES_TABLE . $db->sql_build_array('INSERT', $sql_ary));
			}			
	
		}

		$options = array();
		
		$sql = 'SELECT *
			FROM ' . LINK_TYPES_TABLE . ' 
			WHERE 1 = 1';
		$result = $db->sql_query($sql);

		while ($row = $db->sql_fetchrow($result))
		{
			$options[$row['id']] = array(
				'name'		=> $row['name'],
				'is_source'	=> $row['is_source'],
				);
		}
		$db->sql_freeresult($result);		
		
		$source_options = $type_options = '<option value="0"></option>';
		
		foreach($options as $id => $option)
		{
			$var = ($option['is_source']) ? 'topic_link_source_id' : 'topic_link_type_id';
		
			$sql = 'SELECT COUNT(topic_id) AS num
				FROM ' . TOPICS_TABLE . ' 
				WHERE ' . $var . ' = ' . $id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			$count = $row['num'];
			$db->sql_freeresult($result);		

			$var = ($option['is_source']) ? 'source_options' : 'type_options';
			${$var} .= '<option value="' . $id . '">' . $option['name'];
			${$var} .= ($count) ? ' (' . $count . ')' : '';
			${$var} .= '</option>';
		}
		

		$template->assign_vars(array(
			'LINK_FORUM_ID'				=> $config['link_forum_id'],
			'LINKS_PER_POST'			=> $config['links_per_post'],
			
			'LINK_SOURCE_OPTIONS'		=> $source_options,
			'LINK_TYPE_OPTIONS'			=> $type_options,
			

			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			));
    }

}

?>