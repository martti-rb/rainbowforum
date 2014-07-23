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

class acp_hide
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_hide');
        $this->tpl_name     = 'acp_hide';
        $this->page_title   = 'ACP_HIDE';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{
			set_config('hide_announce_enable', request_var('hide_announce_enable', 0));
			set_config('hide_filters_enable', request_var('hide_filters_enable', 0));			
		}

		$template->assign_vars(array(		
			'S_HIDE_ANNOUNCE_ENABLE'			=> $config['hide_announce_enable'] ? true : false,
			'S_HIDE_FILTERS_ENABLE'				=> $config['hide_filters_enable'] ? true : false,			
			'S_UPDATED'							=> $submit,
			'U_ACTION'							=> $this->u_action,
			'L_TITLE'							=> $user->lang[$this->page_title],			
			));	
    }

}

?>