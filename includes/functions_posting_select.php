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



// link mod begin

class posting_select
{







	function topic_link()
	{
	}

	function text_ary()
	{	
		global $user;
		
		return array(			
			C_LINK_NEW		=> $user->lang['LINK_NEW'],
			C_LINK_BROKEN	=> $user->lang['LINK_BROKEN'],
			C_LINK_OBSOLETE	=> $user->lang['LINK_OBSOLETE'],
			);
	}

	function class_ary()
	{
		return array(			
				C_LINK_NEW		=> 'link-new',
				C_LINK_BROKEN	=> 'link-broken',
				C_LINK_OBSOLETE	=> 'link-obsolete',
				);
	}
	
	function get_text($id)
	{
		$ary = $this->text_ary();
		return $ary[$id];
	}

	function get_class($id)
	{
		$ary = $this->class_ary();
		return $ary[$id];
	}	

	function forum_ary($select_id = false, $ignore_id = false, $omit_id = false, $ignore_empty = false, $ignore_cat = false)
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

			if (($row['forum_type'] == FORUM_CAT && ($row['left_id'] + 1 == $row['right_id']))
				|| ($row['forum_type'] != FORUM_POST))
			{
				$disabled = true;
			}

			$forum_list[$row['forum_id']] = array_merge(array('padding' => $padding, 'disabled' => $disabled), $row);
		}
		$db->sql_freeresult($result);
		unset($padding_store);

		return $forum_list;
	}	
}			


// link mod end


?>