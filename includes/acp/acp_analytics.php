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

class acp_analytics
{
	var $u_action;

    function main($id, $mode)
    {
        global $db, $user, $auth, $template;
        global $config, $phpbb_root_path, $phpbb_admin_path, $phpEx;
                    
        $user->add_lang('mods/acp_analytics');
        $this->tpl_name     = 'acp_analytics';
        $this->page_title   = 'ACP_ANALYTICS';
                            
        // 
        $submit	= isset($_POST['submit']) ? true : false;
		
		if ($submit)
		{			
			set_config('google_analytics_en', request_var('google_analytics_en', 0));
			set_config('statcounter_en', request_var('statcounter_en', 0));			
		}		

		$template->assign_vars(array(		
			'S_GOOGLE_ANALYTICS_EN'				=> $config['google_analytics_en'],
			'S_STATCOUNTER_EN'					=> $config['statcounter_en'],
			
			'S_UPDATED'							=> $submit,
			'S_CALENDAR_INPUT_FORUMS_OPTIONS'	=> make_forum_select($select_ids, $ignore_ids, false, false, false, false, false),
			'U_ACTION'							=> $this->u_action,
			'L_TITLE'							=> $user->lang[$this->page_title],			
			));	
    }

}

?>