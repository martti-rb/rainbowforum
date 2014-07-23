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

class acp_circles
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_circles');
        $this->tpl_name     = 'acp_circles';
        $this->page_title   = 'ACP_CIRCLES';
                         
        $submit = isset($_POST['submit']) ? true : false;

		if ($submit)
		{
			set_config('circle_forum_id', request_var('circle_forum_id', 0));		
		}

		
		$template->assign_vars(array(
			'CIRCLE_FORUM_ID'			=> $config['circle_forum_id'],

			'U_ACTION'					=> $this->u_action,
			'L_TITLE'					=> $user->lang[$this->page_title],
			'S_UPDATED'					=> $submit,
			));
    }

}

?>